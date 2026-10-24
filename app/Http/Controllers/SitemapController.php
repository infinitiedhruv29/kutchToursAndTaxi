<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $packages = Package::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $staticRoutes = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => route('packages'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => route('taxi'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => route('gallery'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('about'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        return response()
            ->view('sitemap', compact('packages', 'staticRoutes'))
            ->header('Content-Type', 'application/xml');
    }
}
