<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Setting;
use App\Models\TaxiRoute;
use App\Models\Testimonial;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $packages = Package::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get();

        $routes = TaxiRoute::where('is_published', true)
            ->where('is_featured', true)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        $vehicles = Vehicle::with(['type', 'capacity'])
            ->where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->take(4)
            ->get();

        $destinations = Destination::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $faqs = Faq::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->take(5)
            ->get();

        $settings = Setting::current();

        return view('index', compact('packages', 'routes', 'vehicles', 'destinations', 'testimonials', 'faqs', 'settings'));
    }
}
