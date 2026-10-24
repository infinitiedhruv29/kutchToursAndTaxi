<?php

namespace Tests\Feature;

use App\Models\CustomPackage;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\TaxiRoute;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminToFrontendEndToEndCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@kutchtoursandtaxi.com',
            'password' => Hash::make('Secret123!'),
        ]);

        Setting::updateOrCreate(
            ['id' => Setting::SINGLETON_ID],
            [
                'business_name' => 'Kutch Tours & Taxi',
                'contact_number' => '+91 99042 22293',
                'whatsapp_number' => '919904222293',
                'contact_email' => 'contact@kutchtoursandtaxi.com',
                'address' => 'Bhuj, Kutch, Gujarat',
                'primary_color' => '#9E2A2B',
            ]
        );
    }

    /**
     * 1. Settings & Branding: Admin update -> Frontend reflection across all pages and dynamic styles.
     */
    public function test_settings_and_branding_end_to_end_crud_and_frontend_reflection(): void
    {
        // 1. Update settings via Admin
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'business_name' => 'Kutch Royal Safari & Taxi',
            'contact_number' => '+91 98765 43210',
            'whatsapp_number' => '919876543210',
            'contact_email' => 'info@kutchroyalsafari.com',
            'address' => 'Near Aina Mahal, Bhuj - Kutch, Gujarat 370001',
            'primary_color' => '#0D9488', // Emerald Teal
            'facebook_url' => 'https://facebook.com/kutchroyalsafari',
            'instagram_url' => 'https://instagram.com/kutchroyalsafari',
            'terms_content' => 'Standard tour terms and booking policy.',
            'privacy_policy' => 'We protect user data privacy.',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/admin/settings');

        // 2. Verify Database
        $this->assertDatabaseHas('settings', [
            'id' => Setting::SINGLETON_ID,
            'business_name' => 'Kutch Royal Safari & Taxi',
            'contact_number' => '+91 98765 43210',
            'whatsapp_number' => '919876543210',
            'contact_email' => 'info@kutchroyalsafari.com',
            'address' => 'Near Aina Mahal, Bhuj - Kutch, Gujarat 370001',
            'primary_color' => '#0D9488',
        ]);

        // 3. Verify Admin Portal Reflects Updated Business Name
        $adminDashboard = $this->actingAs($this->admin)->get('/admin');
        $adminDashboard->assertOk()
            ->assertSee('Kutch Royal Safari &amp; Taxi', false);

        // 4. Verify Frontend Home Page Reflects Contacts, Dynamic Color, and Footer
        $homeResponse = $this->get('/');
        $homeResponse->assertOk()
            ->assertSee('+91 98765 43210')
            ->assertSee('wa.me/919876543210', false)
            ->assertSee('Near Aina Mahal, Bhuj')
            ->assertSee('#0D9488', false)
            ->assertSee('--primary-rgb: 13, 148, 136', false);

        // 5. Verify Frontend About Page Reflects Updated Phone & WhatsApp
        $aboutResponse = $this->get('/about');
        $aboutResponse->assertOk()
            ->assertSee('+91 98765 43210')
            ->assertSee('wa.me/919876543210', false);

        // 6. Verify Frontend Contact Page Reflects Updated Address, Email & WhatsApp
        $contactResponse = $this->get('/contact');
        $contactResponse->assertOk()
            ->assertSee('Near Aina Mahal, Bhuj')
            ->assertSee('info@kutchroyalsafari.com')
            ->assertSee('wa.me/919876543210', false);
    }

    /**
     * 2. Taxi Routes: Admin Create -> DataTable -> Frontend Read -> Admin Update -> Frontend Read -> Unpublish -> Delete.
     */
    public function test_taxi_routes_end_to_end_crud_and_frontend_reflection(): void
    {
        // CREATE
        $createResponse = $this->actingAs($this->admin)->post('/admin/routes', [
            'origin_name' => 'Bhuj',
            'destination_name' => 'Kalo Dungar Hill',
            'title' => 'Bhuj to Kalo Dungar Hill',
            'slug' => 'bhuj-to-kalo-dungar-hill',
            'forward_one_way_fare' => 2500,
            'forward_round_trip_fare' => 3500,
            'distance_km' => 95,
            'duration_hours' => 2,
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);
        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect('/admin/routes');

        $route = TaxiRoute::where('slug', 'bhuj-to-kalo-dungar-hill')->firstOrFail();
        $this->assertEquals(2500, (int) $route->forward_one_way_fare);

        // ADMIN DATATABLE VERIFICATION
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/routes/data?search[value]=Kalo+Dungar');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Bhuj to Kalo Dungar Hill', json_encode($dtResponse->json()));

        // FRONTEND REFLECTION (HOME & TAXI PAGES)
        $homeResponse = $this->get('/');
        $homeResponse->assertOk()
            ->assertSee('Bhuj to Kalo Dungar Hill')
            ->assertSee('₹3,500')
            ->assertSee('wa.me/919904222293?text=Hi%21+I+want+to+book+a+taxi+for+Bhuj+to+Kalo+Dungar+Hill', false);

        $taxiResponse = $this->get('/taxi');
        $taxiResponse->assertOk()
            ->assertSee('Bhuj to Kalo Dungar Hill')
            ->assertSee('95 km')
            ->assertSee('₹2,500')
            ->assertSee('₹3,500');

        // UPDATE
        $updateResponse = $this->actingAs($this->admin)->put('/admin/routes/' . $route->id, [
            'origin_name' => 'Bhuj',
            'destination_name' => 'Kalo Dungar Magnetic Peak',
            'title' => 'Bhuj to Kalo Dungar & Magnetic Hill',
            'slug' => 'bhuj-to-kalo-dungar-hill',
            'forward_one_way_fare' => 2800,
            'forward_round_trip_fare' => 3900,
            'distance_km' => 98,
            'duration_hours' => 2,
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);
        $updateResponse->assertSessionHasNoErrors();

        // FRONTEND REFLECTS UPDATE
        $updatedHome = $this->get('/');
        $updatedHome->assertOk()
            ->assertSee('Bhuj to Kalo Dungar &amp; Magnetic Hill', false)
            ->assertSee('₹3,900');

        $updatedTaxi = $this->get('/taxi');
        $updatedTaxi->assertOk()
            ->assertSee('Bhuj to Kalo Dungar &amp; Magnetic Hill', false)
            ->assertSee('₹2,800')
            ->assertSee('₹3,900');

        // UNPUBLISH
        $this->actingAs($this->admin)->put('/admin/routes/' . $route->id, [
            'origin_name' => 'Bhuj',
            'destination_name' => 'Kalo Dungar Magnetic Peak',
            'title' => 'Bhuj to Kalo Dungar & Magnetic Hill',
            'slug' => 'bhuj-to-kalo-dungar-hill',
            'forward_one_way_fare' => 2800,
            'forward_round_trip_fare' => 3900,
            'is_published' => false,
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        // FRONTEND HIDES UNPUBLISHED ROUTE
        $this->get('/')->assertDontSee('Bhuj to Kalo Dungar &amp; Magnetic Hill', false);
        $this->get('/taxi')->assertDontSee('Bhuj to Kalo Dungar &amp; Magnetic Hill', false);

        // DELETE
        $deleteResponse = $this->actingAs($this->admin)->delete('/admin/routes/' . $route->id);
        $deleteResponse->assertRedirect('/admin/routes');
        $this->assertDatabaseMissing('taxi_routes', ['id' => $route->id]);
    }

    /**
     * 3. Vehicles Fleet, Types & Capacities: Admin Create -> DataTable -> Frontend Read -> Admin Update -> Frontend Read -> Unpublish -> Delete.
     */
    public function test_vehicles_fleet_end_to_end_crud_and_frontend_reflection(): void
    {
        // 1. Create Vehicle Type & Vehicle Capacity via Admin
        $typeResponse = $this->actingAs($this->admin)->post('/admin/vehicle-types', [
            'name' => 'Premium VIP MPV',
            'sort_order' => 1,
        ]);
        $typeResponse->assertRedirect('/admin/vehicle-types');
        $type = VehicleType::where('name', 'Premium VIP MPV')->firstOrFail();

        $capacityResponse = $this->actingAs($this->admin)->post('/admin/vehicle-capacities', [
            'label' => '7 Seater Royal',
            'passenger_capacity' => 7,
            'sort_order' => 1,
        ]);
        $capacityResponse->assertRedirect('/admin/vehicle-capacities');
        $capacity = VehicleCapacity::where('passenger_capacity', 7)->firstOrFail();

        // Check Frontend Taxi filters reflect new Type and Capacity
        $taxiFilters = $this->get('/taxi');
        $taxiFilters->assertOk()
            ->assertSee('Premium VIP MPV')
            ->assertSee('7 Seater Royal');

        // 2. Create Vehicle via Admin
        $createVehResponse = $this->actingAs($this->admin)->post('/admin/vehicles', [
            'name' => 'Toyota Innova Hycross',
            'slug' => 'toyota-innova-hycross',
            'vehicle_type_id' => $type->id,
            'vehicle_capacity_id' => $capacity->id,
            'price_per_km' => 24,
            'min_km_per_day' => 300,
            'rate_per_day' => 7200,
            'notes' => 'Hybrid luxury captain seats with dual AC.',
            'is_published' => true,
            'sort_order' => 1,
        ]);
        $createVehResponse->assertSessionHasNoErrors();
        $createVehResponse->assertRedirect('/admin/vehicles');

        $vehicle = Vehicle::where('slug', 'toyota-innova-hycross')->firstOrFail();

        // Check Admin DataTable
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/vehicles/data?search[value]=Hycross');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Toyota Innova Hycross', json_encode($dtResponse->json()));

        // Check Frontend Home and Taxi
        $homeVeh = $this->get('/');
        $homeVeh->assertOk()
            ->assertSee('Toyota Innova Hycross')
            ->assertSee('Premium VIP MPV')
            ->assertSee('7 Seater Royal')
            ->assertSee('₹24/km');

        $taxiVeh = $this->get('/taxi');
        $taxiVeh->assertOk()
            ->assertSee('Toyota Innova Hycross')
            ->assertSee('₹24/km')
            ->assertSee('Hybrid luxury captain seats');

        // 3. Update Vehicle via Admin
        $updateVehResponse = $this->actingAs($this->admin)->put('/admin/vehicles/' . $vehicle->id, [
            'name' => 'Toyota Innova Hycross ZX',
            'slug' => 'toyota-innova-hycross',
            'vehicle_type_id' => $type->id,
            'vehicle_capacity_id' => $capacity->id,
            'price_per_km' => 27,
            'min_km_per_day' => 300,
            'rate_per_day' => 8100,
            'notes' => 'Top-spec ottoman luxury seats.',
            'is_published' => true,
            'sort_order' => 1,
        ]);
        $updateVehResponse->assertSessionHasNoErrors();

        // Check Frontend reflects update
        $updatedHomeVeh = $this->get('/');
        $updatedHomeVeh->assertOk()
            ->assertSee('Toyota Innova Hycross ZX')
            ->assertSee('₹27/km');

        $updatedTaxiVeh = $this->get('/taxi');
        $updatedTaxiVeh->assertOk()
            ->assertSee('Toyota Innova Hycross ZX')
            ->assertSee('₹27/km');

        // 4. Unpublish Vehicle
        $this->actingAs($this->admin)->put('/admin/vehicles/' . $vehicle->id, [
            'name' => 'Toyota Innova Hycross ZX',
            'slug' => 'toyota-innova-hycross',
            'vehicle_type_id' => $type->id,
            'vehicle_capacity_id' => $capacity->id,
            'is_published' => false,
            'sort_order' => 1,
        ]);

        // Check Frontend hides unpublished vehicle
        $this->get('/')->assertDontSee('Toyota Innova Hycross ZX');
        $this->get('/taxi')->assertDontSee('Toyota Innova Hycross ZX');

        // 5. Delete Vehicle
        $deleteVehResponse = $this->actingAs($this->admin)->delete('/admin/vehicles/' . $vehicle->id);
        $deleteVehResponse->assertRedirect('/admin/vehicles');
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    /**
     * 4. Destinations: Admin Create -> DataTable -> Frontend Read -> Admin Update -> Frontend Read -> Unpublish -> Delete.
     */
    public function test_destinations_end_to_end_crud_and_frontend_reflection(): void
    {
        // CREATE
        $createResponse = $this->actingAs($this->admin)->post('/admin/destinations', [
            'name' => 'Dholavira Harappan Site',
            'short_description' => 'UNESCO World Heritage prehistoric Indus Valley Civilization ruins.',
            'sort_order' => 1,
            'is_published' => true,
            'is_featured' => true,
        ]);
        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect('/admin/destinations');

        $destination = Destination::where('name', 'Dholavira Harappan Site')->firstOrFail();

        // DATATABLE
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/destinations/data?search[value]=Dholavira');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Dholavira Harappan Site', json_encode($dtResponse->json()));

        // FRONTEND REFLECTION
        $homeResponse = $this->get('/');
        $homeResponse->assertOk()
            ->assertSee('Dholavira Harappan Site')
            ->assertSee('UNESCO World Heritage prehistoric Indus Valley');

        // UPDATE
        $updateResponse = $this->actingAs($this->admin)->put('/admin/destinations/' . $destination->id, [
            'name' => 'Dholavira UNESCO World Heritage Site',
            'short_description' => 'World famous 5000-year-old Harappan archaeological marvel.',
            'sort_order' => 1,
            'is_published' => true,
            'is_featured' => true,
        ]);
        $updateResponse->assertSessionHasNoErrors();

        // FRONTEND REFLECTS UPDATE
        $updatedHome = $this->get('/');
        $updatedHome->assertOk()
            ->assertSee('Dholavira UNESCO World Heritage Site')
            ->assertSee('World famous 5000-year-old Harappan');

        // UNPUBLISH
        $this->actingAs($this->admin)->put('/admin/destinations/' . $destination->id, [
            'name' => 'Dholavira UNESCO World Heritage Site',
            'sort_order' => 1,
            'is_published' => false,
            'is_featured' => false,
        ]);

        // FRONTEND HIDES IT
        $this->get('/')->assertDontSee('Dholavira UNESCO World Heritage Site');

        // DELETE
        $deleteResponse = $this->actingAs($this->admin)->delete('/admin/destinations/' . $destination->id);
        $deleteResponse->assertRedirect('/admin/destinations');
        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
    }

    /**
     * 5. FAQs: Admin Create -> DataTable -> Frontend Accordion & Schema -> Admin Update -> Unpublish -> Delete.
     */
    public function test_faqs_end_to_end_crud_and_frontend_reflection(): void
    {
        // CREATE
        $createResponse = $this->actingAs($this->admin)->post('/admin/faqs', [
            'question' => 'Can we customize our taxi route and halts during desert travel?',
            'answer' => '<p>Yes, all outstation and local packages are 100% customizable with your choice of scenic stops.</p>',
            'sort_order' => 1,
            'is_published' => true,
        ]);
        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect('/admin/faqs');

        $faq = Faq::where('question', 'Can we customize our taxi route and halts during desert travel?')->firstOrFail();

        // DATATABLE
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/faqs/data?search[value]=customize');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Can we customize our taxi route', json_encode($dtResponse->json()));

        // FRONTEND REFLECTION (ACCORDION & JSON-LD SCHEMA)
        $homeResponse = $this->get('/');
        $homeResponse->assertOk()
            ->assertSee('Can we customize our taxi route and halts during desert travel?')
            ->assertSee('Yes, all outstation and local packages are 100% customizable')
            ->assertSee('"@type": "FAQPage"', false)
            ->assertSee('Can we customize our taxi route and halts during desert travel?', false);

        // UPDATE
        $updateResponse = $this->actingAs($this->admin)->put('/admin/faqs/' . $faq->id, [
            'question' => 'Are taxi itineraries in Kutch fully customizable with halts?',
            'answer' => '<p>Yes, absolutely! You can add halts at craft villages and viewpoints.</p>',
            'sort_order' => 1,
            'is_published' => true,
        ]);
        $updateResponse->assertSessionHasNoErrors();

        // FRONTEND REFLECTS UPDATE
        $updatedHome = $this->get('/');
        $updatedHome->assertOk()
            ->assertSee('Are taxi itineraries in Kutch fully customizable with halts?')
            ->assertSee('You can add halts at craft villages and viewpoints');

        // UNPUBLISH
        $this->actingAs($this->admin)->put('/admin/faqs/' . $faq->id, [
            'question' => 'Are taxi itineraries in Kutch fully customizable with halts?',
            'answer' => '<p>Yes, absolutely!</p>',
            'sort_order' => 1,
            'is_published' => false,
        ]);

        // FRONTEND HIDES UNPUBLISHED FAQ
        $this->get('/')->assertDontSee('Are taxi itineraries in Kutch fully customizable with halts?');

        // DELETE
        $deleteResponse = $this->actingAs($this->admin)->delete('/admin/faqs/' . $faq->id);
        $deleteResponse->assertRedirect('/admin/faqs');
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    /**
     * 6. Gallery Categories & Photos: Admin Create -> DataTable -> Frontend Filter & Photos -> Admin Update -> Unpublish -> Delete.
     */
    public function test_gallery_categories_and_photos_end_to_end_crud_and_frontend_reflection(): void
    {
        // 1. Create Gallery Category
        $catResponse = $this->actingAs($this->admin)->post('/admin/gallery-categories', [
            'name' => 'Banni Handicrafts & Mud Art',
            'sort_order' => 1,
            'is_published' => true,
        ]);
        $catResponse->assertRedirect('/admin/gallery-categories');
        $category = GalleryCategory::where('name', 'Banni Handicrafts & Mud Art')->firstOrFail();

        // Frontend Gallery Filter Button Reflection
        $galleryPage = $this->get('/gallery');
        $galleryPage->assertOk()
            ->assertSee('Banni Handicrafts &amp; Mud Art', false);

        // 2. Upload Gallery Photo
        $imageFile = UploadedFile::fake()->image('lippan-art.jpg', 800, 600);
        $imgResponse = $this->actingAs($this->admin)->post('/admin/gallery-images', [
            'gallery_category_id' => $category->id,
            'image' => $imageFile,
            'caption' => 'Traditional Lippan Mud Mirror Work',
            'image_alt' => 'Lippan art on Kutchi Bhunga wall',
            'is_published' => true,
            'sort_order' => 1,
        ]);
        $imgResponse->assertSessionHasNoErrors();
        $imgResponse->assertRedirect('/admin/gallery-images');

        $image = GalleryImage::where('caption', 'Traditional Lippan Mud Mirror Work')->firstOrFail();
        Storage::disk('public')->assertExists($image->image_path);

        // Check Admin DataTable
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/gallery-images/data?search[value]=Lippan');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Traditional Lippan Mud Mirror Work', json_encode($dtResponse->json()));

        // Frontend Gallery Photo Grid Reflection
        $galleryView = $this->get('/gallery');
        $galleryView->assertOk()
            ->assertSee('Traditional Lippan Mud Mirror Work')
            ->assertSee('data-cat="' . $category->id . '"', false);

        // 3. Update Gallery Photo Caption
        $updateImg = $this->actingAs($this->admin)->put('/admin/gallery-images/' . $image->id, [
            'gallery_category_id' => $category->id,
            'caption' => 'Authentic Rogan Art & Lippan Mirrors',
            'image_alt' => 'Rogan and Lippan art',
            'is_published' => true,
            'sort_order' => 1,
        ]);
        $updateImg->assertSessionHasNoErrors();

        // Frontend Reflects Updated Caption
        $updatedGallery = $this->get('/gallery');
        $updatedGallery->assertOk()
            ->assertSee('Authentic Rogan Art &amp; Lippan Mirrors', false);

        // 4. Unpublish Photo
        $this->actingAs($this->admin)->put('/admin/gallery-images/' . $image->id, [
            'gallery_category_id' => $category->id,
            'caption' => 'Authentic Rogan Art & Lippan Mirrors',
            'is_published' => false,
            'sort_order' => 1,
        ]);

        // Frontend Hides Photo
        $this->get('/gallery')->assertDontSee('Authentic Rogan Art &amp; Lippan Mirrors', false);

        // 5. Delete Photo
        $this->actingAs($this->admin)->delete('/admin/gallery-images/' . $image->id);
        $this->assertDatabaseMissing('gallery_images', ['id' => $image->id]);

        // 6. Delete Category
        $this->actingAs($this->admin)->delete('/admin/gallery-categories/' . $category->id);
        $this->assertDatabaseMissing('gallery_categories', ['id' => $category->id]);
        $this->get('/gallery')->assertDontSee('Banni Handicrafts &amp; Mud Art', false);
    }

    /**
     * 7. Package Builder (Custom Packages Proposals): Admin Create -> DataTable -> PDF Generation -> Update -> Delete.
     */
    public function test_package_builder_custom_packages_end_to_end_crud_and_pdf_generation(): void
    {
        // CREATE
        $createResponse = $this->actingAs($this->admin)->post('/admin/custom-packages', [
            'client_name' => 'Suresh Bhatt',
            'client_phone' => '9825199999',
            'title' => 'Golden Kutch Family Odyssey',
            'duration' => '3 Nights - 4 Days',
            'highlight' => 'Full Moon White Rann Special',
            'price_2p' => 32000,
            'price_4p' => 45000,
            'price_6p' => 58000,
            'inclusions' => ['AC Private Cab', 'Tent City Stay', 'Desert Safari Permit'],
            'exclusions' => ['Airfare', 'Personal Shopping'],
            'day_title' => ['Arrival Bhuj & Transfer to Tent City', 'White Rann Sunset & Cultural Folk Show', 'Kalo Dungar & Gandhi Nu Gam'],
            'day_description' => ['Check-in and welcome drink.', 'Experience mesmerizing white desert under the moon.', 'Visit magnetic hill and craft village.'],
            'action' => 'save',
        ]);
        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect('/admin/custom-packages');

        $pkg = CustomPackage::where('client_name', 'Suresh Bhatt')->firstOrFail();
        $this->assertEquals('Golden Kutch Family Odyssey', $pkg->title);
        $this->assertCount(3, $pkg->itineraries);

        // DATATABLE
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/custom-packages/data?search[value]=Suresh');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Suresh Bhatt', json_encode($dtResponse->json()));

        // PDF GENERATION
        $pdfResponse = $this->actingAs($this->admin)->get('/admin/custom-packages/' . $pkg->id . '/pdf');
        $pdfResponse->assertOk();
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('Content-Type'));

        // UPDATE
        $updateResponse = $this->actingAs($this->admin)->put('/admin/custom-packages/' . $pkg->id, [
            'client_name' => 'Suresh Bhatt & Family',
            'client_phone' => '9825199999',
            'title' => 'Royal Golden Kutch Odyssey (VIP)',
            'duration' => '4 Nights - 5 Days',
            'price_2p' => 38000,
            'action' => 'save',
        ]);
        $updateResponse->assertSessionHasNoErrors();

        $pkg->refresh();
        $this->assertEquals('Royal Golden Kutch Odyssey (VIP)', $pkg->title);

        // UPDATED PDF STILL RENDERS CLEANLY
        $updatedPdf = $this->actingAs($this->admin)->get('/admin/custom-packages/' . $pkg->id . '/pdf');
        $updatedPdf->assertOk();
        $this->assertEquals('application/pdf', $updatedPdf->headers->get('Content-Type'));

        // DELETE
        $deleteResponse = $this->actingAs($this->admin)->delete('/admin/custom-packages/' . $pkg->id);
        $deleteResponse->assertRedirect('/admin/custom-packages');
        $this->assertDatabaseMissing('custom_packages', ['id' => $pkg->id]);
    }

    /**
     * 8. Invoices & Billing: Admin Create -> DataTable -> PDF Generation -> Update -> Delete.
     */
    public function test_invoices_end_to_end_crud_and_pdf_generation(): void
    {
        // CREATE INVOICE
        $createResponse = $this->actingAs($this->admin)->post('/admin/invoices', [
            'invoice_date' => now()->toDateString(),
            'customer_name' => 'Deepak Varma',
            'customer_contact' => '9898012345',
            'customer_email' => 'deepak@varma.com',
            'customer_address' => 'Satellite, Ahmedabad, Gujarat',
            'customer_gst_number' => '24ABCDE1234F1Z5',
            'has_package' => 1,
            'package_name' => 'Kutch Rann Holiday 3N/4D',
            'package_price' => 30000,
            'tax_rate' => 5,
            'notes' => 'Thank you for choosing Kutch Tours & Taxi.',
            'submit_action' => 'save',
        ]);
        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect('/admin/invoices');

        $invoice = Invoice::where('customer_name', 'Deepak Varma')->firstOrFail();
        $this->assertEquals(30000, (float) $invoice->subtotal);
        $this->assertEquals(1500, (float) $invoice->tax_amount);
        $this->assertEquals(31500, (float) $invoice->total_amount);

        // DATATABLE
        $dtResponse = $this->actingAs($this->admin)->getJson('/admin/invoices/data?search[value]=Deepak');
        $dtResponse->assertOk();
        $this->assertStringContainsString('Deepak Varma', json_encode($dtResponse->json()));

        // PDF GENERATION
        $pdfResponse = $this->actingAs($this->admin)->get('/admin/invoices/' . $invoice->id . '/pdf');
        $pdfResponse->assertOk();
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('Content-Type'));

        // UPDATE
        $updateResponse = $this->actingAs($this->admin)->put('/admin/invoices/' . $invoice->id, [
            'invoice_date' => now()->toDateString(),
            'customer_name' => 'Deepak K. Varma',
            'customer_contact' => '9898012345',
            'customer_email' => 'deepak@varma.com',
            'has_package' => 1,
            'package_name' => 'Kutch Rann Holiday 3N/4D (Deluxe)',
            'package_price' => 34000,
            'tax_rate' => 5,
            'submit_action' => 'save',
        ]);
        $updateResponse->assertSessionHasNoErrors();

        $invoice->refresh();
        $this->assertEquals('Deepak K. Varma', $invoice->customer_name);
        $this->assertEquals(34000, (float) $invoice->subtotal);

        // UPDATED PDF RENDERS
        $updatedPdf = $this->actingAs($this->admin)->get('/admin/invoices/' . $invoice->id . '/pdf');
        $updatedPdf->assertOk();

        // DELETE
        $deleteResponse = $this->actingAs($this->admin)->delete('/admin/invoices/' . $invoice->id);
        $deleteResponse->assertRedirect('/admin/invoices');
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    /**
     * 9. Admin Profile & Authentication Lifecycle.
     */
    public function test_admin_profile_and_authentication_lifecycle(): void
    {
        // 1. Update Profile (changing email requires current_password)
        $profileResponse = $this->actingAs($this->admin)->patch('/admin/profile', [
            'name' => 'Master Administrator',
            'email' => 'superadmin@kutchtoursandtaxi.com',
            'current_password' => 'Secret123!',
        ]);
        $profileResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Master Administrator',
            'email' => 'superadmin@kutchtoursandtaxi.com',
        ]);

        // 2. Change Password
        $passwordResponse = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'Secret123!',
            'password' => 'NewStrongPass987#',
            'password_confirmation' => 'NewStrongPass987#',
        ]);
        $passwordResponse->assertSessionHasNoErrors();

        // 3. Logout
        $logoutResponse = $this->actingAs($this->admin)->post('/admin/logout');
        $logoutResponse->assertRedirect('/admin/login');
        $this->assertGuest();

        // 4. Login with Old Password Fails
        $failLogin = $this->post('/admin/login', [
            'email' => 'superadmin@kutchtoursandtaxi.com',
            'password' => 'Secret123!',
        ]);
        $failLogin->assertSessionHasErrors('email');
        $this->assertGuest();

        // 5. Login with New Credentials Succeeds
        $successLogin = $this->post('/admin/login', [
            'email' => 'superadmin@kutchtoursandtaxi.com',
            'password' => 'NewStrongPass987#',
        ]);
        $successLogin->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin->fresh());
    }
}
