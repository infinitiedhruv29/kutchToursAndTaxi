<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDynamicModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Setting::current();
    }

    private function samplePackageData(array $overrides = []): array
    {
        return array_replace([
            'package_type' => 'Kutch',
            'title' => 'Splendid Rann Cultural Expedition',
            'slug' => 'splendid-rann-cultural-expedition',
            'duration' => '3 Days / 2 Nights',
            'highlight' => 'Best Value',
            'price_2p' => 12500,
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
            'description' => 'Experience the pristine White Desert and traditional artisan villages.',
        ], $overrides);
    }

    private function sampleTestimonialData(array $overrides = []): array
    {
        return array_replace([
            'client_name' => 'Dr. Aakash Verma',
            'service_name' => 'White Rann & Dholavira Family Tour',
            'review' => 'Exceptional service provided by Kutch Tours & Taxi. Our driver Mr. Imran was extremely knowledgeable and polite.',
            'is_published' => true,
            'sort_order' => 1,
            'youtube_link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
        ], $overrides);
    }

    /* -------------------------------------------------------------------------
     * 1. TOUR PACKAGES CRUD & FRONTEND REFLECTION
     * ---------------------------------------------------------------------- */

    public function test_packages_routes_require_authentication(): void
    {
        $package = Package::create($this->samplePackageData());

        $this->get('/admin/packages')->assertRedirect('/admin/login');
        $this->get('/admin/packages/create')->assertRedirect('/admin/login');
        $this->get('/admin/packages/' . $package->id . '/edit')->assertRedirect('/admin/login');
        $this->getJson('/admin/packages/data')->assertUnauthorized();
        $this->postJson('/admin/packages', [])->assertUnauthorized();
        $this->deleteJson('/admin/packages/' . $package->id)->assertUnauthorized();
    }

    public function test_can_create_update_and_delete_tour_package_with_frontend_reflection(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        // 1. Visit index & data
        $this->get('/admin/packages')->assertOk()->assertSee('data-admin-datatable', false);
        $this->get('/admin/packages/create')->assertOk()->assertSee('data-admin-form', false);

        // 2. Create Package with Itinerary and Inclusions
        $storeData = [
            'package_type' => 'Kutch',
            'title' => 'Splendid Rann Cultural Expedition',
            'duration' => '3 Days / 2 Nights',
            'highlight' => 'Best Value',
            'price_2p' => 12500,
            'price_4p' => 22000,
            'price_6p' => 30000,
            'is_published' => 1,
            'is_featured' => 1,
            'sort_order' => 1,
            'description' => 'Experience the pristine White Desert and traditional artisan villages.',
            'day_title' => ['Arrival & Sunset at White Rann', 'Kalo Dungar & Gandhi nu Gam'],
            'day_description' => ['Check in at resort, evening camel safari.', 'Panoramic hilltop views and craft shopping.'],
            'day_spots' => ['White Rann, Tent City', 'Kalo Dungar, Craft Village'],
            'day_note' => ['Dinner included', 'Breakfast and lunch included'],
            'inclusions_text' => "AC Cab throughout the tour\nTraditional Kutchi meals included",
            'exclusions_text' => "Flight or train tickets",
        ];

        $response = $this->post('/admin/packages', $storeData);
        $response->assertRedirect('/admin/packages')->assertSessionHas('status');

        $package = Package::where('title', 'Splendid Rann Cultural Expedition')->firstOrFail();
        $this->assertSame('splendid-rann-cultural-expedition', $package->slug);
        $this->assertSame(12500.0, (float) $package->price_2p);
        $this->assertCount(2, $package->itineraries);
        $this->assertCount(2, $package->features->where('type', 'inclusion'));
        $this->assertCount(1, $package->features->where('type', 'exclusion'));

        // Verify Data endpoint lists it
        $this->getJson('/admin/packages/data')
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1)
            ->assertSee('Splendid Rann Cultural Expedition');

        // Verify Frontend Reflection on /packages, /package-{slug}, and /
        $this->get('/packages')
            ->assertOk()
            ->assertSee('Splendid Rann Cultural Expedition')
            ->assertSee('3 Days / 2 Nights')
            ->assertSee('₹12,500');

        $this->get('/package-' . $package->slug)
            ->assertOk()
            ->assertSee('Splendid Rann Cultural Expedition')
            ->assertSee('Arrival & Sunset at White Rann')
            ->assertSee('Traditional Kutchi meals included');

        $this->get('/')
            ->assertOk()
            ->assertSee('Splendid Rann Cultural Expedition');

        // 3. Edit Form & Update Package
        $this->get('/admin/packages/' . $package->id . '/edit')
            ->assertOk()
            ->assertSee('Splendid Rann Cultural Expedition');

        $updateData = array_merge($storeData, [
            'title' => 'Splendid Rann Grand Safari',
            'price_2p' => 14000,
        ]);

        $this->put('/admin/packages/' . $package->id, $updateData)
            ->assertRedirect('/admin/packages')
            ->assertSessionHas('status');

        $package->refresh();
        $this->assertSame('Splendid Rann Grand Safari', $package->title);
        $this->assertSame(14000.0, (float) $package->price_2p);

        // 4. Delete Package
        $this->deleteJson('/admin/packages/' . $package->id)->assertOk();
        $this->assertDatabaseMissing('packages', ['id' => $package->id]);
        $this->assertDatabaseMissing('package_itineraries', ['package_id' => $package->id]);
        $this->assertDatabaseMissing('package_features', ['package_id' => $package->id]);
    }

    /* -------------------------------------------------------------------------
     * 2. TESTIMONIALS CRUD & FRONTEND REFLECTION
     * ---------------------------------------------------------------------- */

    public function test_testimonials_routes_require_authentication(): void
    {
        $testimonial = Testimonial::create($this->sampleTestimonialData());

        $this->get('/admin/testimonials')->assertRedirect('/admin/login');
        $this->get('/admin/testimonials/create')->assertRedirect('/admin/login');
        $this->get('/admin/testimonials/' . $testimonial->id . '/edit')->assertRedirect('/admin/login');
        $this->getJson('/admin/testimonials/data')->assertUnauthorized();
        $this->postJson('/admin/testimonials', [])->assertUnauthorized();
        $this->deleteJson('/admin/testimonials/' . $testimonial->id)->assertUnauthorized();
    }

    public function test_can_create_update_and_delete_testimonial_with_frontend_reflection(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        // 1. Visit index & create
        $this->get('/admin/testimonials')->assertOk()->assertSee('data-admin-datatable', false);
        $this->get('/admin/testimonials/create')->assertOk()->assertSee('data-admin-form', false);

        // 2. Create Testimonial
        $storeData = [
            'client_name' => 'Dr. Aakash Verma',
            'service_name' => 'White Rann & Dholavira Family Tour',
            'review' => 'Exceptional service provided by Kutch Tours & Taxi. Our driver Mr. Imran was extremely knowledgeable and polite.',
            'is_published' => 1,
            'sort_order' => 1,
            'youtube_link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ];

        $this->post('/admin/testimonials', $storeData)
            ->assertRedirect('/admin/testimonials')
            ->assertSessionHas('status');

        $testimonial = Testimonial::where('client_name', 'Dr. Aakash Verma')->firstOrFail();
        $this->assertSame('dQw4w9WgXcQ', $testimonial->youtube_video_id);

        // Verify Data endpoint
        $this->getJson('/admin/testimonials/data')
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1)
            ->assertSee('Dr. Aakash Verma');

        // Verify Frontend reflection on Home page
        $this->get('/')
            ->assertOk()
            ->assertSee('Dr. Aakash Verma')
            ->assertSee('Exceptional service provided by Kutch Tours & Taxi');

        // 3. Edit Form & Update
        $this->get('/admin/testimonials/' . $testimonial->id . '/edit')
            ->assertOk()
            ->assertSee('Dr. Aakash Verma');

        $this->put('/admin/testimonials/' . $testimonial->id, array_merge($storeData, [
            'client_name' => 'Dr. Aakash Verma & Family',
        ]))->assertRedirect('/admin/testimonials')->assertSessionHas('status');

        $this->assertSame('Dr. Aakash Verma & Family', $testimonial->fresh()->client_name);

        // 4. Delete Testimonial
        $this->deleteJson('/admin/testimonials/' . $testimonial->id)->assertOk();
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    /* -------------------------------------------------------------------------
     * 3. CUSTOMER INQUIRIES WORKFLOW
     * ---------------------------------------------------------------------- */

    public function test_public_contact_submission_stores_inquiry_in_database(): void
    {
        $payload = [
            'name' => 'Meera Bhatt',
            'phone' => '+91 98250 12345',
            'email' => 'meera.bhatt@example.com',
            'service' => 'Tour Package — 3N4D White Rann & Dholavira',
            'travel_date' => '20th to 24th December 2026',
            'passengers' => 4,
            'pickup_location' => 'Bhuj Railway Station',
            'message' => 'Need 1 AC Ertiga with elderly passenger care.',
            'source' => 'contact_page',
        ];

        $response = $this->postJson('/inquiry', $payload);
        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['inquiry_id']);

        $inquiry = Inquiry::where('phone', '+91 98250 12345')->firstOrFail();
        $this->assertSame('Meera Bhatt', $inquiry->name);
        $this->assertSame('new', $inquiry->status);
        $this->assertSame(4, $inquiry->passenger_count);
    }

    public function test_admin_can_manage_inquiries(): void
    {
        $inquiry = Inquiry::create([
            'name' => 'Siddharth Trivedi',
            'phone' => '9898011223',
            'email' => 'siddharth@example.com',
            'service_type' => 'taxi',
            'service_title' => 'AC Cab Hire',
            'travel_date' => '2027-01-10',
            'passenger_count' => 2,
            'origin_name' => 'Ahmedabad Airport',
            'destination_name' => 'Bhuj',
            'message' => 'One way cab to Bhuj.',
            'status' => 'new',
        ]);

        // Unauthenticated access fails
        $this->get('/admin/inquiries')->assertRedirect('/admin/login');
        $this->getJson('/admin/inquiries/data')->assertUnauthorized();
        $this->get('/admin/inquiries/' . $inquiry->id)->assertRedirect('/admin/login');

        $admin = User::factory()->create();
        $this->actingAs($admin);

        // 1. Admin Index & Data
        $this->get('/admin/inquiries')->assertOk()->assertSee('data-admin-datatable', false);
        $this->getJson('/admin/inquiries/data')
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1)
            ->assertSee('Siddharth Trivedi');

        // Test status filtering in datatable
        $this->getJson('/admin/inquiries/data?status=closed')
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 0);

        // 2. View Details
        $this->get('/admin/inquiries/' . $inquiry->id)
            ->assertOk()
            ->assertSee('Siddharth Trivedi')
            ->assertSee('9898011223')
            ->assertSee('Ahmedabad Airport');

        // 3. Update Status and Admin Notes
        $this->put('/admin/inquiries/' . $inquiry->id, [
            'status' => 'contacted',
            'admin_notes' => 'Called customer at 4:30 PM. Quoted ₹6,500. Awaiting flight confirmation.',
        ])->assertRedirect('/admin/inquiries')->assertSessionHas('status');

        $inquiry->refresh();
        $this->assertSame('contacted', $inquiry->status);
        $this->assertSame('Called customer at 4:30 PM. Quoted ₹6,500. Awaiting flight confirmation.', $inquiry->admin_notes);

        // 4. Delete Inquiry
        $this->deleteJson('/admin/inquiries/' . $inquiry->id)->assertOk();
        $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
    }
}
