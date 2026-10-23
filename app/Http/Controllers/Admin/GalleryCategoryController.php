<?php

namespace App\Http\Controllers\Admin;

use App\Http\AdminFeedback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryCategoryRequest;
use App\Models\GalleryCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GalleryCategoryController extends Controller
{
    public function index(): View
    {
        $categories = GalleryCategory::withCount('images')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.gallery.categories.index', compact('categories'));
    }

    public function store(GalleryCategoryRequest $request): JsonResponse|RedirectResponse
    {
        GalleryCategory::create($request->contentData());

        return AdminFeedback::success($request, 'Category created successfully.', route('admin.gallery-categories.index'));
    }

    public function update(GalleryCategoryRequest $request, GalleryCategory $galleryCategory): JsonResponse|RedirectResponse
    {
        $galleryCategory->update($request->contentData());

        return AdminFeedback::success($request, 'Category updated successfully.', route('admin.gallery-categories.index'));
    }

    public function destroy(Request $request, GalleryCategory $galleryCategory): JsonResponse|RedirectResponse
    {
        if ($galleryCategory->images()->exists()) {
            throw ValidationException::withMessages([
                'category' => 'Cannot delete this category because it contains photos. Reassign or delete the photos first.',
            ]);
        }

        $galleryCategory->delete();

        return AdminFeedback::success($request, 'Category deleted successfully.', route('admin.gallery-categories.index'));
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:gallery_categories,id'],
        ]);

        DB::transaction(function () use ($request): void {
            foreach ($request->input('order', []) as $index => $id) {
                GalleryCategory::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['message' => 'Categories reordered successfully.']);
    }
}
