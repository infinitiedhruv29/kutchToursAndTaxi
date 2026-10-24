<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get();

        $count = $packages->count();
        $settings = Setting::current();

        return view('packages', compact('packages', 'count', 'settings'));
    }

    public function show(string $slug): View
    {
        $package = Package::with(['itineraries', 'galleryImages', 'inclusions', 'exclusions'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $otherPackages = Package::where('is_published', true)
            ->where('id', '!=', $package->id)
            ->orderBy('is_featured', 'desc')
            ->take(3)
            ->get();

        $settings = Setting::current();

        return view('package-show', compact('package', 'otherPackages', 'settings'));
    }
}
