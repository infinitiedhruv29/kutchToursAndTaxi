<?php

namespace Tests\Feature;

use App\Models\TaxiRoute;
use App\Models\TaxiRouteFare;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxiRouteVehicleFaresTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function createVehicles(): array
    {
        $sedan = VehicleType::create(['name' => 'Sedan', 'sort_order' => 1]);
        $suv = VehicleType::create(['name' => 'SUV', 'sort_order' => 2]);

        $cap4 = VehicleCapacity::create(['label' => '4 Seater', 'passenger_capacity' => 4, 'sort_order' => 1]);
        $cap6 = VehicleCapacity::create(['label' => '6 Seater', 'passenger_capacity' => 6, 'sort_order' => 2]);

        $dzire = Vehicle::create([
            'name' => 'Swift Dzire',
            'slug' => 'dzire',
            'vehicle_type_id' => $sedan->id,
            'vehicle_capacity_id' => $cap4->id,
            'price_per_km' => 13.00,
            'price_for_12_hrs' => 2500.00,
            'min_km_per_day' => 300,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $ertiga = Vehicle::create([
            'name' => 'Maruti Ertiga',
            'slug' => 'ertiga',
            'vehicle_type_id' => $suv->id,
            'vehicle_capacity_id' => $cap6->id,
            'price_per_km' => 16.00,
            'price_for_12_hrs' => 3200.00,
            'min_km_per_day' => 300,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        return [$dzire, $ertiga];
    }

    public function test_admin_can_create_route_with_multi_vehicle_fares(): void
    {
        $admin = User::factory()->create();
        [$dzire, $ertiga] = $this->createVehicles();

        $response = $this->actingAs($admin)->post('/admin/routes', [
            'origin_name' => 'Bhuj',
            'destination_name' => 'White Rann',
            'title' => 'Bhuj to White Rann',
            'slug' => 'bhuj-to-white-rann',
            'distance_km' => 85.00,
            'duration_hours' => 1,
            'duration_minutes' => 48,
            'forward_one_way_fare' => 3600.00,
            'forward_round_trip_fare' => 7200.00,
            'is_published' => '1',
            'is_featured' => '1',
            'default_vehicle_id' => $dzire->id,
            'vehicle_fares' => [
                0 => [
                    'vehicle_id' => $dzire->id,
                    'one_way_fare' => 3600.00,
                    'round_trip_fare' => 7200.00,
                ],
                1 => [
                    'vehicle_id' => $ertiga->id,
                    'one_way_fare' => 4500.00,
                    'round_trip_fare' => 8500.00,
                ],
            ],
        ]);

        $response->assertRedirect('/admin/routes');

        $route = TaxiRoute::where('slug', 'bhuj-to-white-rann')->firstOrFail();
        $this->assertCount(2, $route->fares);

        $this->assertDatabaseHas('taxi_route_fares', [
            'taxi_route_id' => $route->id,
            'vehicle_id' => $dzire->id,
            'one_way_fare' => 3600.00,
            'round_trip_fare' => 7200.00,
            'is_default' => 1,
        ]);

        $this->assertDatabaseHas('taxi_route_fares', [
            'taxi_route_id' => $route->id,
            'vehicle_id' => $ertiga->id,
            'one_way_fare' => 4500.00,
            'round_trip_fare' => 8500.00,
            'is_default' => 0,
        ]);
    }

    public function test_admin_can_update_route_vehicle_fares(): void
    {
        $admin = User::factory()->create();
        [$dzire, $ertiga] = $this->createVehicles();

        $route = TaxiRoute::create([
            'origin_name' => 'Bhuj',
            'destination_name' => 'Mandvi',
            'title' => 'Bhuj to Mandvi',
            'slug' => 'bhuj-to-mandvi',
            'distance_km' => 60.00,
            'forward_one_way_fare' => 3300.00,
            'forward_round_trip_fare' => 6600.00,
            'is_published' => true,
        ]);

        TaxiRouteFare::create([
            'taxi_route_id' => $route->id,
            'vehicle_id' => $dzire->id,
            'one_way_fare' => 3300.00,
            'round_trip_fare' => 6600.00,
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->put('/admin/routes/' . $route->id, [
            'origin_name' => 'Bhuj',
            'destination_name' => 'Mandvi',
            'title' => 'Bhuj to Mandvi Beach',
            'slug' => 'bhuj-to-mandvi',
            'distance_km' => 60.00,
            'forward_one_way_fare' => 3300.00,
            'forward_round_trip_fare' => 6600.00,
            'is_published' => '1',
            'default_vehicle_id' => $ertiga->id,
            'vehicle_fares' => [
                0 => [
                    'vehicle_id' => $dzire->id,
                    'one_way_fare' => 3500.00,
                    'round_trip_fare' => 7000.00,
                ],
                1 => [
                    'vehicle_id' => $ertiga->id,
                    'one_way_fare' => 4200.00,
                    'round_trip_fare' => 7800.00,
                ],
            ],
        ]);

        $response->assertRedirect('/admin/routes');

        $this->assertDatabaseHas('taxi_route_fares', [
            'taxi_route_id' => $route->id,
            'vehicle_id' => $dzire->id,
            'one_way_fare' => 3500.00,
            'round_trip_fare' => 7000.00,
            'is_default' => 0,
        ]);

        $this->assertDatabaseHas('taxi_route_fares', [
            'taxi_route_id' => $route->id,
            'vehicle_id' => $ertiga->id,
            'one_way_fare' => 4200.00,
            'round_trip_fare' => 7800.00,
            'is_default' => 1,
        ]);
    }

    public function test_frontend_taxi_page_shows_vehicle_selector_and_pricing(): void
    {
        [$dzire, $ertiga] = $this->createVehicles();

        $route = TaxiRoute::create([
            'origin_name' => 'Bhuj',
            'destination_name' => 'White Rann',
            'title' => 'Bhuj to White Rann',
            'slug' => 'bhuj-to-white-rann',
            'distance_km' => 85.00,
            'estimated_duration_minutes' => 108,
            'forward_one_way_fare' => 3600.00,
            'forward_round_trip_fare' => 7200.00,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        TaxiRouteFare::create([
            'taxi_route_id' => $route->id,
            'vehicle_id' => $dzire->id,
            'one_way_fare' => 3600.00,
            'round_trip_fare' => 7200.00,
            'is_default' => true,
            'sort_order' => 1,
        ]);

        TaxiRouteFare::create([
            'taxi_route_id' => $route->id,
            'vehicle_id' => $ertiga->id,
            'one_way_fare' => 4500.00,
            'round_trip_fare' => 8500.00,
            'is_default' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get('/taxi');
        $response->assertOk();
        $response->assertSee('Bhuj to White Rann');
        $response->assertSee('Swift Dzire');
        $response->assertSee('Maruti Ertiga');
        $response->assertSee('Fare (12 hours)');
        $response->assertSee('₹2,500');
        $response->assertSee('₹13/km');
        $response->assertSee('Avg 300 km/day');
        $response->assertSee('12 Hours Duty');
        $response->assertSee('Driver Food Extra');
    }

    public function test_frontend_falls_back_to_round_trip_fare_if_vehicle_lacks_12_hr_price(): void
    {
        $sedan = VehicleType::create(['name' => 'Sedan', 'sort_order' => 1]);
        $cap4 = VehicleCapacity::create(['label' => '4 Seater', 'passenger_capacity' => 4, 'sort_order' => 1]);

        $customCab = Vehicle::create([
            'name' => 'Custom Cab',
            'slug' => 'custom-cab',
            'vehicle_type_id' => $sedan->id,
            'vehicle_capacity_id' => $cap4->id,
            'price_per_km' => 14.00,
            'price_for_12_hrs' => null,
            'min_km_per_day' => 300,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $route = TaxiRoute::create([
            'origin_name' => 'Bhuj',
            'destination_name' => 'Mandvi',
            'title' => 'Bhuj to Mandvi Special',
            'slug' => 'bhuj-to-mandvi-special',
            'distance_km' => 60.00,
            'forward_one_way_fare' => 3000.00,
            'forward_round_trip_fare' => 5500.00,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        TaxiRouteFare::create([
            'taxi_route_id' => $route->id,
            'vehicle_id' => $customCab->id,
            'one_way_fare' => 3000.00,
            'round_trip_fare' => 5500.00,
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/taxi');
        $response->assertOk();
        $response->assertSee('Bhuj to Mandvi Special');
        $response->assertSee('₹5,500');
    }

    public function test_frontend_home_page_shows_route_with_vehicle_dropdown(): void
    {
        [$dzire, $ertiga] = $this->createVehicles();

        $route = TaxiRoute::create([
            'origin_name' => 'Bhuj',
            'destination_name' => 'White Rann',
            'title' => 'Bhuj to White Rann',
            'slug' => 'bhuj-to-white-rann',
            'distance_km' => 85.00,
            'forward_one_way_fare' => 3600.00,
            'forward_round_trip_fare' => 7200.00,
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        TaxiRouteFare::create([
            'taxi_route_id' => $route->id,
            'vehicle_id' => $dzire->id,
            'one_way_fare' => 3600.00,
            'round_trip_fare' => 7200.00,
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Bhuj to White Rann');
        $response->assertSee('Select Vehicle');
        $response->assertSee('Swift Dzire');
        $response->assertSee('Trip Fare (12 hours)');
        $response->assertSee('₹2,500');
        $response->assertSee('12 Hours Duty');
        $response->assertSee('Driver Food Extra');
    }

    public function test_vehicle_display_name_deduplicates_seater_in_brackets(): void
    {
        $type = VehicleType::create(['name' => 'Tempo', 'sort_order' => 1]);
        $cap12 = VehicleCapacity::create(['label' => '12 Seater', 'passenger_capacity' => 12, 'sort_order' => 1]);
        $cap4 = VehicleCapacity::create(['label' => '4 Seater', 'passenger_capacity' => 4, 'sort_order' => 2]);

        $urbania = Vehicle::create([
            'name' => 'Force Urbania (12 Seater)',
            'slug' => 'urbania',
            'vehicle_type_id' => $type->id,
            'vehicle_capacity_id' => $cap12->id,
            'price_per_km' => 31.00,
            'is_published' => true,
        ]);

        $dzire = Vehicle::create([
            'name' => 'Swift Dzire',
            'slug' => 'dzire-plain',
            'vehicle_type_id' => $type->id,
            'vehicle_capacity_id' => $cap4->id,
            'price_per_km' => 12.00,
            'is_published' => true,
        ]);

        $this->assertEquals('Force Urbania (12 Seater)', $urbania->display_name);
        $this->assertEquals('Swift Dzire (4 Seater)', $dzire->display_name);

        $route = TaxiRoute::create([
            'origin_name' => 'Bhuj',
            'destination_name' => 'White Rann',
            'title' => 'Bhuj to White Rann',
            'slug' => 'bhuj-to-white-rann',
            'distance_km' => 85.00,
            'is_published' => true,
            'is_featured' => true,
        ]);

        TaxiRouteFare::create([
            'taxi_route_id' => $route->id,
            'vehicle_id' => $urbania->id,
            'one_way_fare' => 9500.00,
            'round_trip_fare' => 18500.00,
            'is_default' => true,
        ]);

        $taxiRes = $this->get('/taxi');
        $taxiRes->assertSee('Force Urbania (12 Seater)');
        $taxiRes->assertDontSee('Force Urbania (12 Seater) (12 Seater)');

        $homeRes = $this->get('/');
        $homeRes->assertSee('Force Urbania (12 Seater)');
        $homeRes->assertDontSee('Force Urbania (12 Seater) (12 Seater)');
    }
}

