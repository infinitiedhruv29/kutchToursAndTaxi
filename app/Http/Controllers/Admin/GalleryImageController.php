<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryImageRequest;
use App\Http\Requests\Admin\GalleryImageTableRequest;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
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

class GalleryImageController extends Controller
{
    private function columns(): array
    {
        return [
            ['data' => 'image', 'label' => 'Photo', 'orderable' => false],
            ['data' => 'caption', 'label' => 'Caption', 'sort' => 'caption', 'orderable' => true],
            ['data' => 'category', 'label' => 'Category', 'sort' => 'gallery_category_id', 'orderable' => true],
            ['data' => 'visibility', 'label' => 'Status', 'sort' => 'is_published', 'orderable' => true],
            ['data' => 'sort_order', 'label' => 'Order', 'sort' => 'sort_order', 'orderable' => true],
            ['data' => 'actions', 'label' => 'Actions', 'orderable' => false],
        ];
    }

    private function listingQuery(GalleryImageTableRequest $request): Builder
    {
        return GalleryImage::query()
            ->with('category')
            ->when($request->searchTerm() !== '', function (Builder $query) use ($request): void {
                $search = '%' . $request->searchTerm() . '%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('caption', 'like', $search)
                        ->orWhere('image_alt', 'like', $search)
                        ->orWhereRelation('category', 'name', 'like', $search);
                });
            })
            ->when($request->input('status'), fn (Builder $query, string $status) => $query->where('is_published', $status === 'published'))
            ->when($request->filled('category_id'), fn (Builder $query) => $query->where('gallery_category_id', (int) $request->input('category_id')))
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');
    }

    public function index(GalleryImageTableRequest $request): View
    {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.gallery.images.index', [
            'columns' => $this->columns(),
            'categories' => $categories,
            'images' => $this->listingQuery($request)->paginate($request->pageSize())->withQueryString(),
        ]);
    }

    public function data(GalleryImageTableRequest $request): JsonResponse
    {
        $query = $this->listingQuery($request);
        $count = (clone $query)->count();
        $columns = $this->columns();
        $rows = $query->offset($request->offset())->limit($request->pageSize())->get()->map(function (GalleryImage $image) use ($columns): array {
            $row = ['id' => $image->id];
            foreach ($columns as $definition) {
                $column = $definition['data'];
                $row[$column] = view('admin.gallery.images.cell', compact('image', 'column'))->render();
            }

            return $row;
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => GalleryImage::count(),
            'recordsFiltered' => $count,
            'data' => $rows,
        ]);
    }

    private function returnDestination(Request $request): string
    {
        return AdminFeedback::returnDestination($request, 'admin.gallery-images.index', [
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'category_id' => ['nullable', 'integer'],
            'sort' => ['sometimes', Rule::in(['caption', 'gallery_category_id', 'is_published', 'sort_order'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'],
            'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ]);
    }

    public function create(Request $request): View
    {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.gallery.images.form', [
            'image' => new GalleryImage(['sort_order' => 0, 'is_published' => true]),
            'categories' => $categories,
            'returnTo' => $this->returnDestination($request),
        ]);
    }

    public function edit(Request $request, GalleryImage $galleryImage): View
    {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.gallery.images.form', [
            'image' => $galleryImage,
            'categories' => $categories,
            'returnTo' => $this->returnDestination($request),
        ]);
    }

    public function store(GalleryImageRequest $request): JsonResponse|RedirectResponse
    {
        $this->persist($request, new GalleryImage);

        return AdminFeedback::success($request, 'Photo uploaded successfully.', $this->returnDestination($request));
    }

    public function update(GalleryImageRequest $request, GalleryImage $galleryImage): JsonResponse|RedirectResponse
    {
        $this->persist($request, $galleryImage);

        return AdminFeedback::success($request, 'Photo updated successfully.', $this->returnDestination($request));
    }

    private function persist(GalleryImageRequest $request, GalleryImage $image): void
    {
        $newPath = null;
        $oldPath = null;
        try {
            if ($request->hasFile('image')) {
                $newPath = $request->file('image')->store('gallery', 'public');
                if ($newPath === false) {
                    throw new RuntimeException('Unable to store the gallery photo.');
                }
            }

            $saved = DB::transaction(function () use ($request, $image, $newPath, &$oldPath): GalleryImage {
                $record = $image->exists ? GalleryImage::lockForUpdate()->findOrFail($image->id) : $image;
                $oldPath = $record->image_path;
                $data = $request->contentData();

                if (is_string($newPath)) {
                    $data['image_path'] = $newPath;
                }

                if (! $record->fill($data)->save()) {
                    throw new RuntimeException('Unable to save the gallery photo.');
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

    public function destroy(Request $request, GalleryImage $galleryImage): JsonResponse|RedirectResponse
    {
        $path = DB::transaction(function () use ($galleryImage): ?string {
            $record = GalleryImage::lockForUpdate()->findOrFail($galleryImage->id);
            $path = $record->image_path;
            if (! $record->delete()) {
                throw new RuntimeException('Unable to delete the photo.');
            }

            return $path;
        });

        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return AdminFeedback::success($request, 'Photo deleted successfully.', $this->returnDestination($request));
    }
}
