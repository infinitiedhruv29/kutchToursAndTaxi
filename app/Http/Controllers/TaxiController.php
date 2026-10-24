<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TaxiRoute;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Contracts\View\View;

class TaxiController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::with(['type', 'capacity'])
            ->where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $types = VehicleType::orderBy('sort_order', 'asc')->get();
        $capacities = VehicleCapacity::orderBy('passenger_capacity', 'asc')->get();

        $routes = TaxiRoute::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = Setting::current();

        return view('taxi', compact('vehicles', 'types', 'capacities', 'routes', 'settings'));
    }
}
