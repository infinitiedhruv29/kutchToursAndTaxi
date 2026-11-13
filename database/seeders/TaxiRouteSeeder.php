<?php

namespace Database\Seeders;

use App\Models\TaxiRoute;
use App\Models\TaxiRouteFare;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class TaxiRouteSeeder extends Seeder
{
    private function cleanFare(float $amount): float
    {
        if ($amount >= 20000) {
            return round($amount / 1000) * 1000;
        }
        if ($amount >= 5000) {
            return round($amount / 500) * 500;
        }
        return round($amount / 100) * 100;
    }

    public function run(): void
    {
        $routes = [
            [
                'title' => 'Bhuj to White Rann (Dhordo)',
                'slug' => 'bhuj-to-white-rann',
                'origin_name' => 'Bhuj',
                'destination_name' => 'White Rann (Dhordo)',
                'short_description' => 'Direct AC cab from Bhuj railway station/airport to the magnificent White Salt Desert.',
                'description' => '<p>Travel conveniently from Bhuj to the White Rann (Dhordo / Tent City). Our local driver ensures permit assistance and guides you to the best sunset vantage points.</p>',
                'image_path' => 'images/index-page/white-rann-hero.webp',
                'image_alt' => 'Bhuj to White Rann taxi service',
                'distance_km' => 85.00,
                'estimated_duration_minutes' => 108,
                'forward_one_way_fare' => 3600.00,
                'forward_round_trip_fare' => 7200.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 3600.00,
                'reverse_round_trip_fare' => 7200.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Bhuj to Mandvi Beach & Vijay Vilas',
                'slug' => 'bhuj-to-mandvi',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Mandvi Beach',
                'short_description' => 'Day trip covering Vijay Vilas Palace, 72 Jinalay Jain Temple, and sunset at Wind Farm Beach.',
                'description' => '<p>Enjoy a scenic drive through southern Kutch to the port town of Mandvi. Visit the royal summer palace of the Maharao, scenic beaches with camel rides, and ship-building yards.</p>',
                'image_path' => 'images/index-page/mandvi-beach-2.webp',
                'image_alt' => 'Bhuj to Mandvi cab booking',
                'distance_km' => 60.00,
                'estimated_duration_minutes' => 78,
                'forward_one_way_fare' => 3300.00,
                'forward_round_trip_fare' => 6600.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 3300.00,
                'reverse_round_trip_fare' => 6600.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Bhuj to Dholavira (via Road to Heaven)',
                'slug' => 'bhuj-to-dholavira',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Dholavira',
                'short_description' => 'Drive through the iconic Road to Heaven across the salt plains to the UNESCO Harappan city.',
                'description' => '<p>Experience Gujarat\'s most famous highway drive — the Road to Heaven passing directly through the white salt flats into Khadir Bet island to explore ancient Dholavira ruins.</p>',
                'image_path' => 'images/packages-page/package-3n-4d/dholavira.webp',
                'image_alt' => 'Bhuj to Dholavira Road to Heaven taxi',
                'distance_km' => 210.00,
                'estimated_duration_minutes' => 240,
                'forward_one_way_fare' => 4500.00,
                'forward_round_trip_fare' => 6000.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 4500.00,
                'reverse_round_trip_fare' => 6000.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Bhuj to Mata no Madh & Lakhpat Fort',
                'slug' => 'bhuj-to-lakhpat',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Lakhpat Fort',
                'short_description' => 'Western Kutch heritage route: Ashapura Mata Temple, Narayan Sarovar, Koteshwar & Lakhpat.',
                'description' => '<p>Spiritual and historical circuit covering Western Kutch\'s most sacred temples and the massive ghost fortress overlooking the Great Rann and Sindh border.</p>',
                'image_path' => 'images/index-page/lakhpat-fort-new.webp',
                'image_alt' => 'Bhuj to Lakhpat taxi hire',
                'distance_km' => 140.00,
                'estimated_duration_minutes' => 168,
                'forward_one_way_fare' => 3500.00,
                'forward_round_trip_fare' => 4800.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 3500.00,
                'reverse_round_trip_fare' => 4800.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Bhuj to Kalo Dungar & Magnetic Hill',
                'slug' => 'bhuj-to-kalo-dungar',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Kalo Dungar',
                'short_description' => 'Scenic mountain ascent to Kutch\'s highest peak overlooking the endless salt desert expanse.',
                'description' => '<p>Visit the 400-year-old Dattatreya Temple atop Kalo Dungar, stop by the fascinating Magnetic Hill gravity phenomenon, and capture sunset panoramas.</p>',
                'image_path' => 'images/gallery-page/white-rann/kalo-dungar-new.webp',
                'image_alt' => 'Bhuj to Kalo Dungar taxi cab',
                'distance_km' => 95.00,
                'estimated_duration_minutes' => 120,
                'forward_one_way_fare' => 2400.00,
                'forward_round_trip_fare' => 3400.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 2400.00,
                'reverse_round_trip_fare' => 3400.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Bhuj to Ahmedabad Outstation Cab',
                'slug' => 'bhuj-to-ahmedabad',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Ahmedabad',
                'short_description' => 'Fast, safe highway drop or round-trip cab service between Kutch and Ahmedabad.',
                'description' => '<p>Comfortable door-to-door cab transfer with trusted drivers on NH 947 / NH 27. Stopovers at Halvad or Morbi on request.</p>',
                'image_path' => 'images/kutch_taxi_hero.webp',
                'image_alt' => 'Bhuj to Ahmedabad taxi service',
                'distance_km' => 340.00,
                'estimated_duration_minutes' => 360,
                'forward_one_way_fare' => 5800.00,
                'forward_round_trip_fare' => 8800.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 5800.00,
                'reverse_round_trip_fare' => 8800.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'Bhuj to Rajkot Highway Cab',
                'slug' => 'bhuj-to-rajkot',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Rajkot',
                'short_description' => 'Direct AC cab service connecting Kutch to Saurashtra commercial capital Rajkot.',
                'description' => '<p>Smooth 4-lane highway transfer via Gandhidham and Morbi. On-time airport and railway station drops.</p>',
                'image_path' => 'images/kutch_about_road.webp',
                'image_alt' => 'Bhuj to Rajkot taxi service',
                'distance_km' => 230.00,
                'estimated_duration_minutes' => 270,
                'forward_one_way_fare' => 4200.00,
                'forward_round_trip_fare' => 6200.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 4200.00,
                'reverse_round_trip_fare' => 6200.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 7,
            ],
            [
                'title' => 'Kutch-Bhuj to Nepal-Pashupatinath',
                'slug' => 'kutch-bhuj-to-nepal-pashupatinath',
                'origin_name' => 'Kutch-Bhuj',
                'destination_name' => 'Nepal-Pashupatinath',
                'short_description' => 'Pashupatinath Temple in Kathmandu sacred pilgrimage tour with verified driver.',
                'description' => '<p>Long-distance pilgrimage and sightseeing tour from Bhuj all the way to Kathmandu Pashupatinath Temple.</p>',
                'image_path' => 'images/kutch_taxi_hero.webp',
                'image_alt' => 'Bhuj to Nepal Pashupatinath taxi service',
                'distance_km' => 2146.00,
                'estimated_duration_minutes' => 2250,
                'forward_one_way_fare' => 30000.00,
                'forward_round_trip_fare' => 60000.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 30000.00,
                'reverse_round_trip_fare' => 60000.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 8,
            ],
            [
                'title' => 'Kutch-Bhuj to Nepal-Muktinath',
                'slug' => 'kutch-bhuj-to-nepal-muktinath',
                'origin_name' => 'Kutch-Bhuj',
                'destination_name' => 'Nepal-Muktinath',
                'short_description' => 'Kutch bhuj to Nepal Muktinath Temple high-altitude spiritual circuit cab booking.',
                'description' => '<p>Specialized spiritual circuit from Kutch to Nepal Muktinath with experienced mountain road drivers.</p>',
                'image_path' => 'images/kutch_taxi_hero.webp',
                'image_alt' => 'Bhuj to Nepal Muktinath taxi cab',
                'distance_km' => 2155.00,
                'estimated_duration_minutes' => 1920,
                'forward_one_way_fare' => 35000.00,
                'forward_round_trip_fare' => 70000.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 35000.00,
                'reverse_round_trip_fare' => 70000.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 9,
            ],
            [
                'title' => 'Bhuj to Kashmir',
                'slug' => 'bhuj-to-kashmir',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Kashmir',
                'short_description' => 'Have the gaze of heaven on earth and beautiful views with our highway tour taxi.',
                'description' => '<p>Extended luxury cab tour from Bhuj to Srinagar, Gulmarg, and Pahalgam in Kashmir.</p>',
                'image_path' => 'images/kutch_taxi_hero.webp',
                'image_alt' => 'Bhuj to Kashmir cab booking',
                'distance_km' => 1697.00,
                'estimated_duration_minutes' => 1800,
                'forward_one_way_fare' => 17000.00,
                'forward_round_trip_fare' => 35000.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 17000.00,
                'reverse_round_trip_fare' => 35000.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 10,
            ],
            [
                'title' => 'Bhuj to Himachal Pradesh',
                'slug' => 'bhuj-to-himachal-pradesh',
                'origin_name' => 'Bhuj',
                'destination_name' => 'Himachal Pradesh',
                'short_description' => 'Explore the scenic views on the trip to Himachal Pradesh (Shimla, Manali, Kullu).',
                'description' => '<p>Grand North India road trip from Bhuj across Punjab into the hills of Himachal Pradesh.</p>',
                'image_path' => 'images/kutch_taxi_hero.webp',
                'image_alt' => 'Bhuj to Himachal Pradesh cab hire',
                'distance_km' => 1325.00,
                'estimated_duration_minutes' => 1320,
                'forward_one_way_fare' => 23000.00,
                'forward_round_trip_fare' => 46000.00,
                'reverse_enabled' => true,
                'reverse_one_way_fare' => 23000.00,
                'reverse_round_trip_fare' => 46000.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 11,
            ],
        ];

        // 1. Create or update each route record
        foreach ($routes as $routeData) {
            TaxiRoute::updateOrCreate(['slug' => $routeData['slug']], $routeData);
        }

        // 2. Fetch all published vehicles present in the database (supports 7, 10, or more fleet vehicles)
        $allVehicles = Vehicle::where('is_published', true)->orderBy('sort_order')->get();
        if ($allVehicles->isEmpty()) {
            return;
        }

        $allRoutes = TaxiRoute::all();
        $baseDzireRate = 12.0;

        // Find standard sedan/Dzire for default marking
        $defaultVeh = $allVehicles->firstWhere('slug', 'dzire') ?? $allVehicles->first();

        foreach ($allRoutes as $route) {
            $distance = (float) ($route->distance_km ?? 100);
            $baseOneWay = (float) ($route->forward_one_way_fare ?? 3000);
            $baseRoundTrip = (float) ($route->forward_round_trip_fare ?? 6000);

            $order = 1;
            foreach ($allVehicles as $vehicle) {
                $vehRate = (float) ($vehicle->price_per_km ?: 13.0);
                $isDefault = $defaultVeh && $defaultVeh->id === $vehicle->id;

                if ($isDefault || $vehicle->slug === 'dzire') {
                    // For the default sedan, use the exact base fares of the route
                    $oneWay = $baseOneWay;
                    $roundTrip = $baseRoundTrip;
                } else {
                    // Calculate round trip: proportion of rate vs Dzire base or distance * rate (with min 300 km day rule)
                    $ratio = $vehRate / $baseDzireRate;
                    $distRoundTripKm = max($distance * 2, (float) ($vehicle->min_km_per_day ?: 300));
                    $distBased = $distRoundTripKm * $vehRate;

                    $calculatedRoundTrip = max($baseRoundTrip * $ratio, $distBased);
                    if ($vehicle->price_for_12_hrs && $distance <= 150) {
                        $calculatedRoundTrip = max($calculatedRoundTrip, (float) $vehicle->price_for_12_hrs);
                    }
                    $roundTrip = $this->cleanFare($calculatedRoundTrip);

                    // One way: ~50-55% of round trip or proportional to base one way
                    $calculatedOneWay = max($baseOneWay * $ratio, $roundTrip * 0.55);
                    $oneWay = $this->cleanFare($calculatedOneWay);
                }

                TaxiRouteFare::updateOrCreate(
                    [
                        'taxi_route_id' => $route->id,
                        'vehicle_id' => $vehicle->id,
                    ],
                    [
                        'one_way_fare' => $oneWay,
                        'round_trip_fare' => $roundTrip,
                        'is_default' => $isDefault,
                        'sort_order' => $order++,
                    ]
                );
            }
        }
    }
}
