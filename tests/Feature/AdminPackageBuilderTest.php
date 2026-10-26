<?php

namespace Tests\Feature;

use App\Models\CustomPackage;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPackageBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_custom_packages_routes_require_authentication(): void
    {
        $this->get('/admin/custom-packages')->assertRedirect('/admin/login');
        $this->get('/admin/custom-packages/create')->assertRedirect('/admin/login');
        $this->get('/admin/custom-packages/data')->assertRedirect('/admin/login');
    }

    public function test_can_create_update_and_delete_custom_package(): void
    {
        $user = User::factory()->create();

        // Create
        $response = $this->actingAs($user)->post('/admin/custom-packages', [
            'client_name' => 'Amit Patel',
            'client_phone' => '+91 98765 43210',
            'title' => 'Rann Utsav Deluxe Package',
            'duration' => '3 Nights / 4 Days',
            'highlight' => 'Full Moon Experience',
            'price_2p' => 19500,
            'price_4p' => 26000,
            'price_6p' => 34000,
            'inclusions_text' => "Private AC Sedan\nDriver Allowance\nAll Tolls & Taxes",
            'exclusions_text' => "Hotel Stays\nMeals & Entry Tickets",
            'day_title' => ['Arrival in Bhuj', 'Bhuj to Dhordo Tent City', 'Kalo Dungar & Return'],
            'day_spots' => ["Aina Mahal\nPrag Mahal", 'White Rann Sunset', 'Dattatreya Temple'],
            'day_description' => ['Arrive at Bhuj and explore heritage monuments.', 'Check into resort and witness breathtaking sunset.', 'Visit the highest point of Kutch and departure.'],
            'day_note' => ['Stay in Bhuj', 'Stay in Dhordo', 'Drop at Bhuj Railway Station'],
            'action' => 'save',
        ]);

        $response->assertRedirect(route('admin.custom-packages.index'));
        $this->assertDatabaseHas('custom_packages', [
            'client_name' => 'Amit Patel',
            'title' => 'Rann Utsav Deluxe Package',
            'duration' => '3 Nights / 4 Days',
        ]);

        $package = CustomPackage::first();
        $this->assertCount(3, $package->itineraries);
        $this->assertEquals('Arrival in Bhuj', $package->itineraries[0]['title']);
        $this->assertEquals(['Aina Mahal', 'Prag Mahal'], $package->itineraries[0]['spots']);
        $this->assertCount(3, $package->inclusions);
        $this->assertCount(2, $package->exclusions);

        // Edit view
        $editResponse = $this->actingAs($user)->get('/admin/custom-packages/' . $package->id . '/edit');
        $editResponse->assertOk();
        $editResponse->assertSee('Rann Utsav Deluxe Package');

        // Update
        $updateResponse = $this->actingAs($user)->put('/admin/custom-packages/' . $package->id, [
            'client_name' => 'Amit Patel',
            'client_phone' => '+91 98765 43210',
            'title' => 'Rann Utsav Deluxe Package - Revised',
            'duration' => '4 Nights / 5 Days',
            'price_2p' => 22000,
            'price_4p' => 29000,
            'price_6p' => 38000,
            'inclusions_text' => "Private AC Sedan\nDriver Allowance",
            'exclusions_text' => 'Entry Tickets',
            'day_title' => ['Arrival & Mandvi', 'Bhuj to White Rann'],
            'day_spots' => ['Vijay Vilas Palace', 'White Desert'],
            'day_description' => ['Visit beach and palace', 'Desert experience'],
            'day_note' => ['Stay in Mandvi', 'Stay in Tent City'],
            'action' => 'save',
        ]);

        $updateResponse->assertRedirect(route('admin.custom-packages.index'));
        $package->refresh();
        $this->assertEquals('Rann Utsav Deluxe Package - Revised', $package->title);
        $this->assertEquals('4 Nights / 5 Days', $package->duration);
        $this->assertCount(2, $package->itineraries);

        // DataTables JSON feed
        $dataResponse = $this->actingAs($user)->getJson('/admin/custom-packages/data');
        $dataResponse->assertOk();
        $dataResponse->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

        // Delete
        $deleteResponse = $this->actingAs($user)->delete('/admin/custom-packages/' . $package->id);
        $deleteResponse->assertRedirect(route('admin.custom-packages.index'));
        $this->assertDatabaseMissing('custom_packages', ['id' => $package->id]);
    }

    public function test_can_download_pdf_for_custom_package(): void
    {
        $user = User::factory()->create();
        Setting::create([
            'business_name' => 'Kutch Tours & Taxi',
            'contact_number' => '+91 99042 22293',
            'contact_email' => 'info@kutchtoursandtaxi.com',
            'address' => 'Bhuj, Kutch',
        ]);

        $package = CustomPackage::create([
            'client_name' => 'Dr. Kulkarni',
            'client_phone' => '+91 98765 00000',
            'title' => 'Kutch Cultural & Heritage Tour',
            'duration' => '2N / 3D',
            'highlight' => 'Special handicraft tour',
            'price_2p' => 15000,
            'price_4p' => 20000,
            'price_6p' => 26000,
            'inclusions' => ['AC Vehicle', 'Driver allowance', 'Tolls'],
            'exclusions' => ['Meals', 'Hotel'],
            'itineraries' => [
                [
                    'day_number' => 1,
                    'title' => 'Bhuj Heritage',
                    'description' => 'Sightseeing of historic sites.',
                    'spots' => ['Aina Mahal', 'Prag Mahal'],
                    'note' => 'Night stay in Bhuj',
                ],
            ],
        ]);

        $response = $this->actingAs($user)->get('/admin/custom-packages/' . $package->id . '/pdf');
        $response->assertOk();
        $this->assertTrue(str_contains((string) $response->headers->get('content-type'), 'application/pdf'));
    }

    public function test_validation_fails_for_invalid_custom_package_input(): void
    {
        $user = User::factory()->create();

        // Missing client_name and title
        $response = $this->actingAs($user)->post('/admin/custom-packages', [
            'client_phone' => '123', // invalid phone
        ]);

        $response->assertSessionHasErrors(['client_name', 'title', 'client_phone']);
    }

    public function test_export_action_returns_download_and_return_urls_on_create_and_update(): void
    {
        $user = User::factory()->create();

        // Create with export action
        $createResponse = $this->actingAs($user)->postJson('/admin/custom-packages', [
            'client_name' => 'Vijay Shah',
            'client_phone' => '+91 98765 11111',
            'title' => 'Dholavira & Rann Special',
            'duration' => '2N / 3D',
            'submit_action' => 'export',
        ]);

        $createResponse->assertOk();
        $createResponse->assertJsonStructure(['message', 'redirect_url', 'download_url', 'return_url']);
        $this->assertTrue(str_contains($createResponse->json('download_url'), '/pdf'));
        $this->assertEquals(route('admin.custom-packages.index'), $createResponse->json('return_url'));
        $this->assertDatabaseHas('custom_packages', ['client_name' => 'Vijay Shah']);

        $package = CustomPackage::where('client_name', 'Vijay Shah')->firstOrFail();

        // Update with export action
        $updateResponse = $this->actingAs($user)->putJson('/admin/custom-packages/' . $package->id, [
            'client_name' => 'Vijay Shah (Updated)',
            'client_phone' => '+91 98765 11111',
            'title' => 'Dholavira & Rann Special - Extended',
            'duration' => '3N / 4D',
            'submit_action' => 'export',
        ]);

        $updateResponse->assertOk();
        $updateResponse->assertJsonStructure(['message', 'redirect_url', 'download_url', 'return_url']);
        $this->assertTrue(str_contains($updateResponse->json('download_url'), '/pdf'));
        $this->assertEquals(route('admin.custom-packages.index'), $updateResponse->json('return_url'));
        $this->assertDatabaseHas('custom_packages', ['client_name' => 'Vijay Shah (Updated)']);
    }
}
