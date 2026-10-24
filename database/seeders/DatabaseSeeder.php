<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            VehicleSeeder::class,
            TaxiRouteSeeder::class,
            PackageSeeder::class,
            DestinationSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
            GallerySeeder::class,
        ]);
    }
}
