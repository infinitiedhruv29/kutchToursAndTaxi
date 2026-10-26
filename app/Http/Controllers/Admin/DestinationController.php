<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestinationRequest;
use App\Http\Requests\Admin\DestinationTableRequest;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class DestinationController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'destination', 'label' => 'Destination', 'sort' => 'name', 'orderable' => true],
            ['data' => 'visibility', 'label' => 'Visibility', 'orderable' => false],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(DestinationTableRequest $request): Builder
    {
        return Destination::query()->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
            $search = '%'.$request->searchTerm().'%';
            $query->where('name', 'like', $search);
        })->when($request->input('status'), fn (Builder $query, string $status) => $query->where('is_published', $status === 'published'))
            ->when($request->input('featured'), fn (Builder $query, string $featured) => $query->where('is_featured', $featured === 'yes'))
            ->orderBy($request->sortColumn(), $request->sortDirection())->orderByDesc('id');
    }

    public function index(DestinationTableRequest $request): View
    {
        return view('admin.destinations.index', ['columns' => $this->columns(), 'destinations' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString()]);
    }

    public function data(DestinationTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Destination $destination) use ($columns): array {
            $row = ['id' => $destination->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.destinations.cell', compact('destination', 'column'))->render();
            }

            return $row;
        });

        return response()->json(['draw' => (int) $request->input('draw', 0), 'recordsTotal' => Destination::count(), 'recordsFiltered' => $count, 'data' => $rows]);
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.destinations.index', [
            'q' => ['nullable', 'string', 'max:255'], 'status' => ['nullable', Rule::in(['published', 'draft'])], 'featured' => ['nullable', Rule::in(['yes', 'no'])],
            'sort' => ['sometimes', Rule::in(['name', 'sort_order'])], 'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'], 'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.destinations.form', ['destination' => new Destination, 'returnTo' => $this->returnDestination($request)]);
    }

    public function edit(Request $request, Destination $destination): View
    {
        return view('admin.destinations.form', ['destination' => $destination, 'returnTo' => $this->returnDestination($request)]);
    }

    public function store(DestinationRequest $request): JsonResponse|RedirectResponse
    {
        $this->persist($request, new Destination);

        return AdminFeedback::success($request, 'Destination created.', $this->returnDestination($request));
    }

    public function update(DestinationRequest $request, Destination $destination): JsonResponse|RedirectResponse
    {
        $this->persist($request, $destination);

        return AdminFeedback::success($request, 'Destination updated.', $this->returnDestination($request));
    }

    private function persist(DestinationRequest $request, Destination $destination): void
    {
        $newPath = null;
        $oldPath = null;
        try {
            if ($request->hasFile('image')) {
                $newPath = $request->file('image')->store('destinations', 'public');
                if ($newPath === false) {
                    throw new RuntimeException('Unable to store the destination image.');
                }
            }
            $saved = DB::transaction(function () use ($request, $destination, $newPath, &$oldPath): Destination {
                $record = $destination->exists ? Destination::lockForUpdate()->findOrFail($destination->id) : $destination;
                $oldPath = $record->image_path;
                $data = $request->contentData();
                if (is_string($newPath)) {
                    $data['image_path'] = $newPath;
                } elseif ($request->boolean('remove_image')) {
                    $data['image_path'] = null;
                }
                if (! $record->fill($data)->save()) {
                    throw new RuntimeException('Unable to save the destination.');
                }

                return $record;
            });
        } catch (Throwable $exception) {
            if (is_string($newPath)) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }
        if ($oldPath && $oldPath !== $saved->image_path) {
            Storage::disk('public')->delete($oldPath);
        }
    }

    public function destroy(Request $request, Destination $destination): JsonResponse|RedirectResponse
    {
        $path = DB::transaction(function () use ($destination): ?string {
            $record = Destination::lockForUpdate()->findOrFail($destination->id);
            $path = $record->image_path;
            if (! $record->delete()) {
                throw new RuntimeException('Unable to delete the destination.');
            }

            return $path;
        });
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return AdminFeedback::success($request, 'Destination deleted.', $this->returnDestination($request));
    }
}
