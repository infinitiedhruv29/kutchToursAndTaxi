<?php

namespace App\Http\Controllers;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $categories = GalleryCategory::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $images = GalleryImage::with('category')
            ->where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = Setting::current();

        return view('gallery', compact('categories', 'images', 'settings'));
    }
}
