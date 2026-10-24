<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Http\Requests\Admin\PackageTableRequest;
use App\Models\Package;
use App\Models\PackageFeature;
use App\Models\PackageItinerary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PackageController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'package', 'label' => 'Package Title', 'sort' => 'title', 'orderable' => true],
            ['data' => 'type', 'label' => 'Type', 'sort' => 'package_type', 'orderable' => true],
            ['data' => 'duration', 'label' => 'Duration', 'sort' => 'duration', 'orderable' => true],
            ['data' => 'pricing', 'label' => 'Pricing (2P / 4P / 6P)', 'sort' => 'price_2p', 'orderable' => true],
            ['data' => 'status', 'label' => 'Status', 'sort' => 'is_published', 'orderable' => true],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(PackageTableRequest $request): Builder
    {
        return Package::query()
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = '%' . $request->searchTerm() . '%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('title', 'like', $search)
                        ->orWhere('duration', 'like', $search)
                        ->orWhere('highlight', 'like', $search)
                        ->orWhere('description', 'like', $search);
                });
            })
            ->when($request->input('package_type'), function (Builder $query, string $type): void {
                if ($type !== 'all') {
                    $query->where('package_type', $type);
                }
            })
            ->when($request->input('status'), function (Builder $query, string $status): void {
                if ($status === 'published') {
                    $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    $query->where('is_published', false);
                }
            })
            ->when($request->input('featured'), function (Builder $query, string $featured): void {
                if ($featured === 'yes') {
                    $query->where('is_featured', true);
                } elseif ($featured === 'no') {
                    $query->where('is_featured', false);
                }
            })
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');
    }

    public function index(PackageTableRequest $request): View
    {
        return view('admin.packages.index', [
            'columns' => $this->columns(),
            'packages' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString(),
        ]);
    }

    public function data(PackageTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (Package $package) use ($columns): array {
            $row = ['id' => $package->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.packages.cell', compact('package', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => Package::count(),
            'recordsFiltered' => $count,
            'data' => $rows,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.packages.form', [
            'package' => new Package([
                'package_type' => 'Kutch',
                'sort_order' => 0,
                'is_published' => true,
                'is_featured' => false,
            ]),
            'returnTo' => $request->input('return_to', route('admin.packages.index')),
        ]);
    }

    public function store(PackageRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->packageData();
        $storedImage = null;

        if ($request->hasFile('image')) {
            $storedImage = $request->file('image')->store('packages', 'public');
            if ($storedImage === false) {
                throw new RuntimeException('Failed to upload package image.');
            }
            $data['image_path'] = $storedImage;
        }

        try {
            DB::transaction(function () use ($data, $request): void {
                $package = Package::create($data);

                // Save Itineraries
                foreach ($request->formatItineraries() as $day) {
                    $package->itineraries()->create($day);
                }

                // Save Inclusions
                foreach ($request->formatInclusions() as $order => $inc) {
                    $package->features()->create([
                        'content' => $inc,
                        'type' => 'inclusion',
                        'sort_order' => $order + 1,
                    ]);
                }

                // Save Exclusions
                foreach ($request->formatExclusions() as $order => $exc) {
                    $package->features()->create([
                        'content' => $exc,
                        'type' => 'exclusion',
                        'sort_order' => $order + 1,
                    ]);
                }
            });
        } catch (Throwable $e) {
            if ($storedImage) {
                Storage::disk('public')->delete($storedImage);
            }
            throw $e;
        }

        return AdminFeedback::success($request, 'Tour package created successfully.', route('admin.packages.index'));
    }

    public function edit(Request $request, Package $package): View
    {
        $package->load(['itineraries', 'features']);

        return view('admin.packages.form', [
            'package' => $package,
            'returnTo' => $request->input('return_to', route('admin.packages.index')),
        ]);
    }

    public function update(PackageRequest $request, Package $package): RedirectResponse|JsonResponse
    {
        $data = $request->packageData();
        $oldImage = $package->image_path;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')->store('packages', 'public');
            if ($newImage === false) {
                throw new RuntimeException('Failed to upload package image.');
            }
            $data['image_path'] = $newImage;
        } elseif ($request->boolean('remove_image')) {
            $data['image_path'] = null;
        }

        try {
            DB::transaction(function () use ($package, $data, $request): void {
                $package->update($data);

                // Sync Itineraries
                $package->itineraries()->delete();
                foreach ($request->formatItineraries() as $day) {
                    $package->itineraries()->create($day);
                }

                // Sync Features (Inclusions & Exclusions)
                $package->features()->delete();
                foreach ($request->formatInclusions() as $order => $inc) {
                    $package->features()->create([
                        'content' => $inc,
                        'type' => 'inclusion',
                        'sort_order' => $order + 1,
                    ]);
                }
                foreach ($request->formatExclusions() as $order => $exc) {
                    $package->features()->create([
                        'content' => $exc,
                        'type' => 'exclusion',
                        'sort_order' => $order + 1,
                    ]);
                }
            });
        } catch (Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }
            throw $e;
        }

        if ($oldImage && $oldImage !== $package->fresh()->image_path && str_starts_with($oldImage, 'packages/')) {
            Storage::disk('public')->delete($oldImage);
        }

        return AdminFeedback::success($request, 'Tour package updated successfully.', route('admin.packages.index'));
    }

    public function destroy(Request $request, Package $package): RedirectResponse|JsonResponse
    {
        $oldImage = $package->image_path;
        $package->delete();

        if ($oldImage && str_starts_with($oldImage, 'packages/')) {
            Storage::disk('public')->delete($oldImage);
        }

        return AdminFeedback::success($request, 'Tour package deleted successfully.', route('admin.packages.index'));
    }
}
