<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\AdminFeedback;
use App\Http\Requests\Admin\StoreTaxiRouteRequest;
use App\Http\Requests\Admin\TaxiRouteRequest;
use App\Http\Requests\Admin\TaxiRouteTableRequest;
use App\Http\Requests\Admin\UpdateTaxiRouteRequest;
use App\Models\TaxiRoute;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class TaxiRouteController extends Controller
{
    public function index(TaxiRouteTableRequest $request): View
    {
        $filters = $request->validated();
        $routes = $this->listingQuery($request)->paginate($request->pageSize())->withQueryString();
        $columns = [
            ['data' => 'route', 'label' => 'Route', 'sort' => 'title', 'orderable' => true],
            ['data' => 'fares', 'label' => 'Forward fares', 'orderable' => false],
            ['data' => 'reverse', 'label' => 'Reverse', 'orderable' => false],
            ['data' => 'visibility', 'label' => 'Visibility', 'orderable' => false],
            ['data' => 'display_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];

        return view('admin.routes.index', compact('routes', 'filters', 'columns'));
    }

    public function data(TaxiRouteTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $filtered = (clone $query)->count();
        $data = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (TaxiRoute $taxiRoute): array {
            $row = ['id' => $taxiRoute->id];
            foreach (['route', 'fares', 'reverse', 'visibility', 'display_order', 'actions'] as $column) {
                $row[$column] = view('admin.routes.cell', compact('taxiRoute', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => TaxiRoute::query()->count(),
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    private function listingQuery(TaxiRouteTableRequest $request): Builder
    {
        return TaxiRoute::query()
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = $request->searchTerm();
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('origin_name', 'like', '%'.$search.'%')
                        ->orWhere('destination_name', 'like', '%'.$search.'%');
                });
            })
            ->when($request->input('status'), fn (Builder $query, string $status) => $query->where('is_published', $status === 'published'))
            ->orderBy($request->sortColumn(), $request->sortDirection())->orderByDesc('id');
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.routes.index', [
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'sort' => ['sometimes', Rule::in(['title', 'sort_order'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'],
            'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.routes.form', ['taxiRoute' => new TaxiRoute, 'returnTo' => $this->returnDestination($request)]);
    }

    public function store(StoreTaxiRouteRequest $request): RedirectResponse|JsonResponse
    {
        $route = $this->persist($request, new TaxiRoute);

        return AdminFeedback::success($request, 'Taxi route created.', $this->returnDestination($request));
    }

    public function edit(Request $request, TaxiRoute $taxiRoute): View
    {
        return view('admin.routes.form', ['taxiRoute' => $taxiRoute, 'returnTo' => $this->returnDestination($request)]);
    }

    public function update(UpdateTaxiRouteRequest $request, TaxiRoute $taxiRoute): RedirectResponse|JsonResponse
    {
        $route = $this->persist($request, $taxiRoute);

        return AdminFeedback::success($request, 'Taxi route updated.', $this->returnDestination($request));
    }

    private function persist(TaxiRouteRequest $request, TaxiRoute $taxiRoute): TaxiRoute
    {
        $newPath = null;
        $oldPath = null;

        try {
            if ($request->hasFile('image')) {
                $newPath = $request->file('image')->store('routes', 'public');
                if ($newPath === false) {
                    throw new RuntimeException('Unable to store the route image.');
                }
            }

            for ($attempt = 0; $attempt < 3; $attempt++) {
                try {
                    $saved = DB::transaction(function () use ($request, $taxiRoute, $newPath, &$oldPath): TaxiRoute {
                        $route = $taxiRoute->exists ? TaxiRoute::query()->lockForUpdate()->findOrFail($taxiRoute->id) : new TaxiRoute;
                        $data = $request->routeData();
                        if ($request->generatedSlug()) {
                            $data['slug'] = TaxiRoute::uniqueSlug($request->slugBase());
                        }
                        $oldPath = $route->image_path;
                        if (is_string($newPath)) {
                            $data['image_path'] = $newPath;
                        } elseif ($request->boolean('remove_image')) {
                            $data['image_path'] = null;
                        }

                        if (! $route->fill($data)->save()) {
                            throw new RuntimeException('Unable to save the taxi route.');
                        }

                        return $route;
                    });
                    break;
                } catch (UniqueConstraintViolationException $exception) {
                    if (! $request->generatedSlug() || $attempt === 2) {
                        throw ValidationException::withMessages(['slug' => 'This URL slug is already in use. Please choose another or try saving again.']);
                    }
                }
            }
        } catch (Throwable $exception) {
            if (is_string($newPath)) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }

        if ($oldPath && $oldPath !== $saved->image_path) {
            Storage::disk('public')->delete($oldPath);
        }

        return $saved;
    }

    public function destroy(Request $request, TaxiRoute $taxiRoute): RedirectResponse|JsonResponse
    {
        $path = DB::transaction(function () use ($taxiRoute): ?string {
            $route = TaxiRoute::query()->lockForUpdate()->findOrFail($taxiRoute->id);
            $path = $route->image_path;
            if (! $route->delete()) {
                throw new RuntimeException('Unable to delete the taxi route.');
            }

            return $path;
        });
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        $message = 'Taxi route deleted. Existing inquiries have been preserved.';
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'redirect_url' => route('admin.routes.index')]);
        }

        return redirect()->route('admin.routes.index')->with('status', $message);
    }
}
