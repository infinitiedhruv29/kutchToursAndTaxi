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

    public function storeInquiry(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceTitle = $request->input('service_title') ?? $request->input('service');
        $passengerCount = $request->input('passenger_count') ?? $request->input('passengers');
        $originName = $request->input('origin_name') ?? $request->input('pickup_location');
        $travelDate = $request->input('travel_date');
        // If travel date is not a strict Y-m-d date, keep as null or try parsing
        $parsedDate = null;
        if ($travelDate) {
            try {
                $parsedDate = \Carbon\Carbon::parse($travelDate)->toDateString();
            } catch (\Throwable $e) {
                // If text like "15th Dec 2026", Carbon::parse often handles it, otherwise keep message intact
            }
        }

        $inquiry = \App\Models\Inquiry::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'service_type' => $request->input('service_type', 'general'),
            'service_title' => $serviceTitle,
            'travel_date' => $parsedDate,
            'passenger_count' => $passengerCount ? (int) $passengerCount : null,
            'origin_name' => $originName,
            'destination_name' => $request->input('destination_name'),
            'message' => $request->input('message'),
            'status' => 'new',
        ]);

        return response()->json(['success' => true, 'inquiry_id' => $inquiry->id]);
    }
}
