<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Setting;
use App\Models\TaxiRoute;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        Setting::updateOrCreate(
            ['id' => Setting::SINGLETON_ID],
            [
                'business_name' => 'Kutch Tours & Taxi',
                'contact_number' => '+91 99042 22293',
                'whatsapp_number' => '919904222293',
                'contact_email' => 'contact@kutchtoursandtaxi.com',
                'address' => 'Bhuj, Kutch, Gujarat',
            ]
        );
    }

    public function test_home_page_loads_successfully_with_packages_and_fleet(): void
    {
        $package = Package::create([
            'title' => 'White Rann & Dholavira (3N/4D)',
            'slug' => 'whiterann-dholavira',
            'duration' => '3 Nights - 4 Days',
            'description' => 'Test description for White Rann package',
            'price_2p' => 28000,
            'is_published' => true,
            'is_featured' => true,
        ]);

        $route = TaxiRoute::create([
            'title' => 'Bhuj to White Rann',
            'slug' => 'bhuj-to-white-rann',
            'origin_name' => 'Bhuj',
            'destination_name' => 'White Rann',
            'forward_one_way_fare' => 2200,
            'forward_round_trip_fare' => 3200,
            'is_published' => true,
            'is_featured' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Kutch Tours & Taxi')
            ->assertSee('White Rann &amp; Dholavira', false)
            ->assertSee('Bhuj to White Rann')
            ->assertSee('wa.me/919904222293', false);
    }

    public function test_taxi_services_page_loads_with_vehicles_and_fares(): void
    {
        $type = VehicleType::create(['name' => 'Sedan', 'sort_order' => 1]);
        $capacity = VehicleCapacity::create(['label' => '4 Seater', 'passenger_capacity' => 4, 'sort_order' => 1]);

        $vehicle = Vehicle::create([
            'name' => 'Swift Dzire',
            'slug' => 'dzire',
            'vehicle_type_id' => $type->id,
            'vehicle_capacity_id' => $capacity->id,
            'price_per_km' => 13,
            'min_km_per_day' => 300,
            'is_published' => true,
        ]);

        $response = $this->get('/taxi');

        $response->assertOk()
            ->assertSee('Taxi Services in Kutch &amp; Bhuj', false)
            ->assertSee('Swift Dzire')
            ->assertSee('₹13/km');
    }

    public function test_packages_index_and_show_pages_load_successfully(): void
    {
        $package = Package::create([
            'title' => 'Kutch Classic Tour',
            'slug' => 'kutch-classic',
            'duration' => '2 Nights - 3 Days',
            'description' => 'Explore Mandvi and Western Kutch',
            'price_2p' => 24000,
            'is_published' => true,
        ]);

        $package->itineraries()->create([
            'day_number' => 1,
            'title' => 'Mandvi Beach Exploration',
            'description' => 'Visit Vijay Vilas and beach',
            'spots' => ['Vijay Vilas Palace', 'Wind Farm Beach'],
        ]);

        // Index
        $indexResponse = $this->get('/packages');
        $indexResponse->assertOk()
            ->assertSee('Kutch Classic Tour')
            ->assertSee('₹24,000');

        // Show
        $showResponse = $this->get('/package-kutch-classic');
        $showResponse->assertOk()
            ->assertSee('Kutch Classic Tour')
            ->assertSee('Mandvi Beach Exploration')
            ->assertSee('Vijay Vilas Palace')
            ->assertSee('wa.me/919904222293', false);
    }

    public function test_gallery_page_loads_successfully(): void
    {
        $response = $this->get('/gallery');
        $response->assertOk()
            ->assertSee('Photo Gallery')
            ->assertSee('Glimpses of Extraordinary Kutch');
    }

    public function test_about_page_loads_successfully(): void
    {
        $response = $this->get('/about');
        $response->assertOk()
            ->assertSee('About Us')
            ->assertSeeText('Welcome to Kutch Tours & Taxi');
    }

    public function test_contact_page_loads_successfully_with_whatsapp_redirect_form(): void
    {
        $response = $this->get('/contact');
        $response->assertOk()
            ->assertSee('Get in Touch With Our Team')
            ->assertSee('Send an Instant Booking Inquiry')
            ->assertSee('Send Booking Inquiry to WhatsApp');
    }

    public function test_sitemap_xml_returns_valid_xml_with_packages(): void
    {
        Package::create([
            'title' => 'Rann & Dholavira Discovery',
            'slug' => 'rann-dholavira-discovery',
            'duration' => '3N/4D',
            'description' => 'Tour of Rann and Dholavira',
            'price_2p' => 25000,
            'is_published' => true,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('/packages', false)
            ->assertSee('/taxi', false)
            ->assertSee('/package-rann-dholavira-discovery', false);
    }

    public function test_seo_schemas_rendered_on_pages(): void
    {
        $package = Package::create([
            'title' => 'Dholavira Special',
            'slug' => 'dholavira-special',
            'duration' => '2N/3D',
            'description' => 'Explore the UNESCO site',
            'price_2p' => 20000,
            'is_published' => true,
        ]);

        // Home Schema
        $homeResponse = $this->get('/');
        $homeResponse->assertOk()
            ->assertSee('TravelAgency', false)
            ->assertSee('PostalAddress', false);

        // Taxi Schema
        $taxiResponse = $this->get('/taxi');
        $taxiResponse->assertOk()
            ->assertSee('TaxiService', false)
            ->assertSee('BreadcrumbList', false);

        // Package Show Schema
        $pkgResponse = $this->get('/package-dholavira-special');
        $pkgResponse->assertOk()
            ->assertSee('TouristTrip', false)
            ->assertSee('Dholavira Special', false)
            ->assertSee('BreadcrumbList', false);
    }
}

