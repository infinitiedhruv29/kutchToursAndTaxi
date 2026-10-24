<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Vehicle Types
        $sedan = VehicleType::updateOrCreate(['name' => 'Sedan'], ['sort_order' => 1]);
        $suv = VehicleType::updateOrCreate(['name' => 'SUV'], ['sort_order' => 2]);
        $traveller = VehicleType::updateOrCreate(['name' => 'Tempo / MiniBus'], ['sort_order' => 3]);

        // Vehicle Capacities
        $cap4 = VehicleCapacity::updateOrCreate(['label' => '4 Seater'], ['passenger_capacity' => 4, 'sort_order' => 1]);
        $cap6 = VehicleCapacity::updateOrCreate(['label' => '6 Seater'], ['passenger_capacity' => 6, 'sort_order' => 2]);
        $cap7 = VehicleCapacity::updateOrCreate(['label' => '7 Seater'], ['passenger_capacity' => 7, 'sort_order' => 3]);
        $cap13 = VehicleCapacity::updateOrCreate(['label' => '13 Seater'], ['passenger_capacity' => 13, 'sort_order' => 4]);
        $cap17 = VehicleCapacity::updateOrCreate(['label' => '17 Seater'], ['passenger_capacity' => 17, 'sort_order' => 5]);

        $vehicles = [
            [
                'name' => 'Swift Dzire',
                'slug' => 'dzire',
                'vehicle_type_id' => $sedan->id,
                'vehicle_capacity_id' => $cap4->id,
                'price_per_km' => 13.00,
                'min_km_per_day' => 300,
                'notes' => 'Perfect for couples & small families (4 passengers)',
                'image_path' => 'images/taxi-page/dzire.webp',
                'image_alt' => 'Swift Dzire AC cab for Kutch tours and taxi hire',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Hyundai Aura',
                'slug' => 'aura',
                'vehicle_type_id' => $sedan->id,
                'vehicle_capacity_id' => $cap4->id,
                'price_per_km' => 13.00,
                'min_km_per_day' => 300,
                'notes' => 'Comfortable sedan for local Bhuj and outstation routes',
                'image_path' => 'images/taxi-page/aura.webp',
                'image_alt' => 'Hyundai Aura AC car rental in Bhuj Kutch',
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Maruti Ertiga',
                'slug' => 'ertiga',
                'vehicle_type_id' => $suv->id,
                'vehicle_capacity_id' => $cap6->id,
                'price_per_km' => 16.00,
                'min_km_per_day' => 300,
                'notes' => 'Spacious 6-seater AC cab with ample luggage room',
                'image_path' => 'images/taxi-page/ertiga.webp',
                'image_alt' => 'Maruti Ertiga AC SUV for Kutch family tours',
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Toyota Innova',
                'slug' => 'innova',
                'vehicle_type_id' => $suv->id,
                'vehicle_capacity_id' => $cap7->id,
                'price_per_km' => 19.00,
                'min_km_per_day' => 300,
                'notes' => 'Rugged and reliable 7-seater for highway & desert terrain',
                'image_path' => 'images/taxi-page/innova.webp',
                'image_alt' => 'Toyota Innova AC cab booking in Kutch',
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Toyota Innova Crysta',
                'slug' => 'innova-crysta',
                'vehicle_type_id' => $suv->id,
                'vehicle_capacity_id' => $cap7->id,
                'price_per_km' => 22.00,
                'min_km_per_day' => 300,
                'notes' => 'Premium luxury SUV with captain seats and dual AC',
                'image_path' => 'images/taxi-page/innova-crysta.webp',
                'image_alt' => 'Toyota Innova Crysta luxury taxi rental Bhuj',
                'is_published' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Force Urbania (12 Seater)',
                'slug' => 'urbania',
                'vehicle_type_id' => $traveller->id,
                'vehicle_capacity_id' => $cap13->id,
                'price_per_km' => 32.00,
                'min_km_per_day' => 300,
                'notes' => 'Executive luxury van with recliner seats for corporate and family groups',
                'image_path' => 'images/taxi-page/urbania.webp',
                'image_alt' => 'Force Urbania luxury van rental in Kutch Gujarat',
                'is_published' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Force Tempo Traveller (17 Seater)',
                'slug' => 'traveller',
                'vehicle_type_id' => $traveller->id,
                'vehicle_capacity_id' => $cap17->id,
                'price_per_km' => 26.00,
                'min_km_per_day' => 300,
                'notes' => 'Ideal for large tour groups, schools & wedding delegates',
                'image_path' => 'images/taxi-page/traveller.webp',
                'image_alt' => 'Tempo Traveller 17 seater rental in Bhuj Kutch',
                'is_published' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($vehicles as $data) {
            Vehicle::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
