<?php

namespace Tests\Feature;

use App\Models\TaxiRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class AdminTaxiRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    private function routeData(array $overrides = []): array
    {
        return array_replace([
            'origin_name' => 'Bhuj', 'destination_name' => 'Mandvi',
            'title' => 'Bhuj to Mandvi', 'slug' => 'bhuj-to-mandvi',
        ], $overrides);
    }

    private function makeRoute(array $overrides = []): TaxiRoute
    {
        return TaxiRoute::create($this->routeData($overrides));
    }

    private function signIn(): void
    {
        $this->actingAs(User::factory()->create());
    }

    public function test_all_route_endpoints_require_authentication(): void
    {
        $route = $this->makeRoute();
        $this->get('/admin/routes')->assertRedirect('/admin/login');
        $this->get('/admin/routes/create')->assertRedirect('/admin/login');
        $this->get('/admin/routes/'.$route->id.'/edit')->assertRedirect('/admin/login');
        $this->post('/admin/routes', $this->routeData())->assertRedirect('/admin/login');
        $this->put('/admin/routes/'.$route->id, $this->routeData())->assertRedirect('/admin/login');
        $this->delete('/admin/routes/'.$route->id)->assertRedirect('/admin/login');
        $this->assertDatabaseCount('taxi_routes', 1);
    }

    public function test_empty_listing_and_form_render_in_existing_admin(): void
    {
        $this->signIn();
        $this->get('/admin/routes')->assertOk()->assertSee('No routes found')
            ->assertSee('data-return-link', false)->assertSee(route('admin.routes.create'), false);
        $this->get('/admin/routes/create')->assertOk()->assertSee('Create taxi route')
            ->assertSee('data-rich-editor', false)->assertSee('data-editor-source', false)
            ->assertSee('Allow reverse travel')->assertSee('SEO description');
    }

    public function test_create_requires_core_details_and_suggests_title_and_slug_server_side(): void
    {
        $this->signIn();
        $this->post('/admin/routes', [])->assertSessionHasErrors(['origin_name', 'destination_name', 'title', 'slug']);
        $this->post('/admin/routes', ['origin_name' => 'Bhuj', 'destination_name' => 'Mandvi'])
            ->assertSessionHasNoErrors()->assertRedirect('/admin/routes');
        $route = TaxiRoute::firstOrFail();
        $this->assertSame('Bhuj to Mandvi', $route->title);
        $this->assertSame('bhuj-to-mandvi', $route->slug);
        $this->assertFalse($route->is_published);
        $this->assertFalse($route->reverse_enabled);
        $this->assertNull($route->description);
    }

    public function test_duplicate_automatic_slugs_receive_suffixes_and_custom_duplicates_fail(): void
    {
        $this->makeRoute();
        $this->signIn();
        $this->post('/admin/routes', ['origin_name' => 'Bhuj', 'destination_name' => 'Mandvi', 'auto_slug' => '1'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('taxi_routes', ['slug' => 'bhuj-to-mandvi-2']);
        $this->post('/admin/routes', $this->routeData())->assertSessionHasErrors('slug');
        $this->post('/admin/routes', $this->routeData(['slug' => 'custom-route', 'title' => 'Custom title']))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('taxi_routes', ['title' => 'Custom title', 'slug' => 'custom-route']);
    }

    public function test_automatic_slug_retries_a_unique_constraint_collision(): void
    {
        $existing = $this->makeRoute();
        $attempts = 0;
        TaxiRoute::creating(function (TaxiRoute $route) use (&$attempts, $existing): void {
            if ($attempts++ === 0) {
                $route->slug = $existing->slug;
            }
        });
        $this->signIn();
        $this->post('/admin/routes', ['origin_name' => 'Bhuj', 'destination_name' => 'Mandvi', 'auto_slug' => '1'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('taxi_routes', ['slug' => 'bhuj-to-mandvi-2']);
        $this->assertSame(2, $attempts);
    }

    public function test_manual_collision_during_save_returns_validation_error_and_cleans_upload(): void
    {
        $existing = $this->makeRoute();
        TaxiRoute::creating(function (TaxiRoute $route) use ($existing): void {
            $route->slug = $existing->slug;
        });
        $this->signIn();
        $this->post('/admin/routes', $this->routeData([
            'slug' => 'otherwise-unique', 'image' => UploadedFile::fake()->image('route.png'),
        ]))->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('taxi_routes', 1);
        $this->assertSame([], Storage::disk('public')->allFiles('routes'));
    }

    public function test_update_preserves_slug_and_ignores_untrusted_fields(): void
    {
        $route = $this->makeRoute();
        $other = $this->makeRoute(['slug' => 'another-route']);
        $this->signIn();
        $this->put('/admin/routes/'.$route->id, $this->routeData([
            'origin_name' => 'Anjar', 'title' => 'Anjar taxi',
            'id' => $other->id, 'image_path' => 'injected.png', 'estimated_duration_minutes' => 100,
            'vehicle_id' => 99, 'destination_id' => 99,
        ]))->assertSessionHasNoErrors()->assertRedirect('/admin/routes');
        $this->assertSame('bhuj-to-mandvi', $route->fresh()->slug);
        $this->assertSame('Anjar', $route->fresh()->origin_name);
        $this->assertNull($route->fresh()->image_path);
        $this->assertNull($route->fresh()->estimated_duration_minutes);
        $this->assertSame('Bhuj', $other->fresh()->origin_name);
        $this->put('/admin/routes/'.$route->id, $this->routeData(['slug' => 'another-route']))->assertSessionHasErrors('slug');
        $this->put('/admin/routes/'.$route->id, $this->routeData(['slug' => 'changed-route']))->assertSessionHasNoErrors();
        $this->assertSame('changed-route', $route->fresh()->slug);
    }

    public function test_core_validation_and_publishing_without_optional_content(): void
    {
        $this->signIn();
        $this->post('/admin/routes', $this->routeData([
            'origin_name' => ' BHUJ ', 'destination_name' => 'bhuj', 'slug' => 'Invalid Slug',
        ]))->assertSessionHasErrors(['destination_name', 'slug']);
        $this->post('/admin/routes', $this->routeData([
            'origin_name' => ['bad'], 'title' => ['bad'], 'is_published' => 'not-boolean',
        ]))->assertSessionHasErrors(['origin_name', 'title', 'is_published']);
        $this->post('/admin/routes', $this->routeData(['is_published' => '1', 'is_featured' => '1', 'sort_order' => 4]))
            ->assertSessionHasNoErrors();
        $route = TaxiRoute::firstOrFail();
        $this->assertTrue($route->is_published);
        $this->assertTrue($route->is_featured);
        $this->assertSame(4, $route->sort_order);
        $this->assertNull($route->image_path);
        $this->put('/admin/routes/'.$route->id, $this->routeData())->assertSessionHasNoErrors();
        $this->assertFalse($route->fresh()->is_published);
        $this->assertFalse($route->fresh()->is_featured);
    }

    public function test_duration_and_distance_conversion_and_validation(): void
    {
        $this->signIn();
        $this->post('/admin/routes', $this->routeData(['duration_hours' => '2', 'duration_minutes' => '35', 'distance_km' => '67.50']))
            ->assertSessionHasNoErrors();
        $route = TaxiRoute::firstOrFail();
        $this->assertSame(155, $route->estimated_duration_minutes);
        $this->assertSame('67.50', $route->distance_km);
        $this->put('/admin/routes/'.$route->id, $this->routeData(['duration_hours' => 71582788, 'duration_minutes' => 59]))
            ->assertSessionHasErrors('duration_hours');
        $this->put('/admin/routes/'.$route->id, $this->routeData(['duration_hours' => -1, 'duration_minutes' => 60, 'distance_km' => '1.234']))
            ->assertSessionHasErrors(['duration_hours', 'duration_minutes', 'distance_km']);
        $this->put('/admin/routes/'.$route->id, $this->routeData())->assertSessionHasNoErrors();
        $this->assertNull($route->fresh()->estimated_duration_minutes);
        $this->put('/admin/routes/'.$route->id, $this->routeData(['duration_minutes' => '0']))->assertSessionHasNoErrors();
        $this->assertSame(0, $route->fresh()->estimated_duration_minutes);
    }

    public function test_reverse_fares_inherit_and_explicit_zero_is_preserved(): void
    {
        $this->signIn();
        $data = $this->routeData([
            'reverse_enabled' => '1', 'forward_one_way_fare' => '1500.25', 'forward_round_trip_fare' => '2500',
        ]);
        $this->post('/admin/routes', $data)->assertSessionHasNoErrors();
        $route = TaxiRoute::firstOrFail();
        $this->assertSame('1500.25', $route->effectiveReverseOneWayFare());
        $this->assertSame('2500.00', $route->effectiveReverseRoundTripFare());
        $this->put('/admin/routes/'.$route->id, $data + ['reverse_one_way_fare' => '0', 'reverse_round_trip_fare' => '3000.50'])
            ->assertSessionHasNoErrors();
        $route->refresh();
        $this->assertSame('0.00', $route->effectiveReverseOneWayFare());
        $this->assertSame('3000.50', $route->effectiveReverseRoundTripFare());
        $this->put('/admin/routes/'.$route->id, array_replace($data, ['forward_one_way_fare' => '1700']))
            ->assertSessionHasNoErrors();
        $this->assertSame('1700.00', $route->fresh()->effectiveReverseOneWayFare());
        $this->assertNull($route->fresh()->reverse_one_way_fare);
    }

    public function test_disabling_reverse_travel_retains_overrides_and_reenabling_restores_them(): void
    {
        $route = $this->makeRoute([
            'reverse_enabled' => true, 'reverse_one_way_fare' => '1800', 'reverse_round_trip_fare' => '3200',
        ]);
        $this->signIn();
        $this->put('/admin/routes/'.$route->id, $this->routeData())->assertSessionHasNoErrors();
        $route->refresh();
        $this->assertFalse($route->reverse_enabled);
        $this->assertNull($route->effectiveReverseOneWayFare());
        $this->assertSame('1800.00', $route->reverse_one_way_fare);
        $this->assertSame('3200.00', $route->reverse_round_trip_fare);
        $this->get('/admin/routes/'.$route->id.'/edit')->assertOk()->assertSee('value="1800.00"', false);
        $this->put('/admin/routes/'.$route->id, $this->routeData([
            'reverse_enabled' => 1, 'reverse_one_way_fare' => $route->reverse_one_way_fare,
            'reverse_round_trip_fare' => $route->reverse_round_trip_fare,
        ]))->assertSessionHasNoErrors();
        $this->assertSame('1800.00', $route->fresh()->effectiveReverseOneWayFare());
    }

    public function test_fare_validation_rejects_negative_excess_precision_and_overflow(): void
    {
        $this->signIn();
        $this->post('/admin/routes', $this->routeData([
            'forward_one_way_fare' => '-1', 'forward_round_trip_fare' => '100.001',
            'reverse_one_way_fare' => '10000000000', 'reverse_round_trip_fare' => '1e3', 'reverse_enabled' => '1',
        ]))->assertSessionHasErrors(['forward_one_way_fare', 'forward_round_trip_fare', 'reverse_one_way_fare', 'reverse_round_trip_fare']);
        $this->assertDatabaseCount('taxi_routes', 0);
    }

    public function test_empty_fares_display_price_on_inquiry(): void
    {
        $route = $this->makeRoute(['reverse_enabled' => true]);
        $this->assertNull($route->effectiveReverseOneWayFare());
        $this->assertNull($route->effectiveReverseRoundTripFare());
        $this->signIn();
        $this->get('/admin/routes')->assertOk()->assertSee('Price on inquiry');
    }

    public function test_description_formatting_round_trips_and_unsafe_html_is_removed(): void
    {
        $this->signIn();
        $html = '<h2>Journey</h2><h3>Details</h3><p><strong>Taxi</strong> <em>service</em><br>Available</p><ul><li>One-way</li></ul><ol><li>Round-trip</li></ol><p><a href="https://example.com" onclick="alert(1)" style="position:fixed">Learn more</a></p>';
        $this->post('/admin/routes', $this->routeData([
            'description' => $html.'<script>alert("bad")</script><iframe src="https://example.com">bad frame</iframe><img src=x onerror="alert(1)"><p style="color:red" class="unsafe">Safe <a href="javascript:alert(1)">bad link</a><a href="data:text/html,bad">data link</a></p>',
        ]))->assertSessionHasNoErrors();
        $route = TaxiRoute::firstOrFail();
        foreach (['<h2>Journey</h2>', '<h3>Details</h3>', '<strong>Taxi</strong>', '<em>service</em>', '<ul>', '<ol>', 'href="https://example.com"'] as $expected) {
            $this->assertStringContainsString($expected, $route->description);
        }
        foreach (['script', 'iframe', '<img', 'onclick', 'onerror', 'style=', 'class=', 'javascript:', 'data:', 'bad frame', 'alert('] as $unsafe) {
            $this->assertStringNotContainsString($unsafe, $route->description);
        }
        $this->get('/admin/routes/'.$route->id.'/edit')->assertOk()
            ->assertSee('&lt;h2&gt;Journey&lt;/h2&gt;', false)->assertDontSee('<h2>Journey</h2>', false);
        $this->put('/admin/routes/'.$route->id, $this->routeData(['description' => $route->description]))->assertSessionHasNoErrors();
        $this->assertStringContainsString('<strong>Taxi</strong>', $route->fresh()->description);
    }

    public function test_empty_editor_markup_is_stored_as_null(): void
    {
        $this->signIn();
        $this->post('/admin/routes', $this->routeData(['description' => '<p><br></p><p>&nbsp; </p>']))->assertSessionHasNoErrors();
        $this->assertNull(TaxiRoute::firstOrFail()->description);
    }

    public function test_validation_redirect_retains_rich_text_and_shows_errors(): void
    {
        $this->signIn();
        $this->from('/admin/routes/create')->post('/admin/routes', $this->routeData([
            'destination_name' => 'BHuj', 'description' => '<p><strong>Keep this</strong></p>',
        ]))->assertSessionHasErrors('destination_name');
        $this->withCookie(config('session.cookie'), session()->getId())->get('/admin/routes/create')
            ->assertOk()->assertSee('The origin and destination must be different.')
            ->assertSee('&lt;p&gt;&lt;strong&gt;Keep this&lt;/strong&gt;&lt;/p&gt;', false);
    }

    public function test_search_status_ordering_and_pagination_are_correct(): void
    {
        for ($index = 1; $index <= 17; $index++) {
            $this->makeRoute(['title' => 'Published route '.$index, 'slug' => 'published-'.$index, 'is_published' => true, 'sort_order' => 5]);
        }
        $priority = $this->makeRoute(['title' => 'Priority route', 'slug' => 'priority', 'is_published' => true, 'sort_order' => 0]);
        $this->makeRoute(['title' => 'Draft route', 'slug' => 'draft', 'destination_name' => 'Ahmedabad']);
        $this->signIn();
        $this->get('/admin/routes?q=route&status=published')->assertOk()
            ->assertViewHas('routes', fn ($routes): bool => $routes->total() === 18 && $routes->count() === 15 && $routes->first()->id === $priority->id)
            ->assertSee('status=published', false)->assertSee('q=route', false)->assertDontSee('Draft route');
        $this->get('/admin/routes?q=Ahmedabad&status=draft')->assertOk()->assertSee('Draft route')
            ->assertViewHas('routes', fn ($routes): bool => $routes->total() === 1);
        $this->get('/admin/routes?q=Bhuj&status=published&page=2')->assertOk()
            ->assertViewHas('routes', fn ($routes): bool => $routes->count() === 3);
        $this->get('/admin/routes?status=draft')->assertOk()->assertViewHas('routes', fn ($routes): bool => $routes->total() === 1);
        $this->get('/admin/routes?status=invalid')->assertSessionHasErrors('status');
    }

    public function test_same_order_routes_are_newest_first_and_zero_search_is_not_ignored(): void
    {
        $older = $this->makeRoute(['slug' => 'older', 'title' => 'Route 0']);
        $newer = $this->makeRoute(['slug' => 'newer', 'title' => 'Other route']);
        $this->signIn();
        $this->get('/admin/routes')->assertViewHas('routes', fn ($routes): bool => $routes->first()->id === $newer->id);
        $this->get('/admin/routes?q=0')->assertViewHas('routes', fn ($routes): bool => $routes->total() === 1 && $routes->first()->id === $older->id);
    }

    public function test_images_upload_replace_remove_and_use_same_origin_urls(): void
    {
        $this->signIn();
        $this->post('/admin/routes', $this->routeData(['image' => UploadedFile::fake()->image('route.jpg'), 'image_alt' => 'A road in Kutch']))
            ->assertSessionHasNoErrors();
        $route = TaxiRoute::firstOrFail();
        $first = $route->image_path;
        Storage::disk('public')->assertExists($first);
        $this->get('/admin/routes/'.$route->id.'/edit')->assertOk()->assertSee('src="/storage/'.$first.'"', false);
        $this->put('/admin/routes/'.$route->id, $this->routeData([
            'image' => UploadedFile::fake()->image('new.webp'), 'remove_image' => '1',
        ]))->assertSessionHasNoErrors();
        $second = $route->fresh()->image_path;
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);
        $this->assertNotSame($first, $second);
        $this->put('/admin/routes/'.$route->id, $this->routeData(['remove_image' => '1']))->assertSessionHasNoErrors();
        $this->assertNull($route->fresh()->image_path);
        Storage::disk('public')->assertMissing($second);
    }

    public function test_image_validation_rejects_svg_and_large_files(): void
    {
        $this->signIn();
        $this->post('/admin/routes', $this->routeData(['image' => UploadedFile::fake()->create('unsafe.svg', 10, 'image/svg+xml')]))
            ->assertSessionHasErrors('image');
        $this->post('/admin/routes', $this->routeData(['image' => UploadedFile::fake()->image('large.png')->size(2049)]))
            ->assertSessionHasErrors('image');
        $this->assertDatabaseCount('taxi_routes', 0);
    }

    public function test_failed_save_cleans_new_upload_and_preserves_existing_route_and_image(): void
    {
        $old = UploadedFile::fake()->image('old.png')->store('routes', 'public');
        $route = $this->makeRoute(['image_path' => $old]);
        TaxiRoute::saving(function (): void {
            throw new RuntimeException('Simulated save failure');
        });
        $this->withoutExceptionHandling();
        $this->signIn();
        try {
            $this->put('/admin/routes/'.$route->id, $this->routeData([
                'title' => 'Must not save', 'image' => UploadedFile::fake()->image('new.png'),
            ]));
            $this->fail('Expected save failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated save failure', $exception->getMessage());
        }
        $this->assertSame('Bhuj to Mandvi', $route->fresh()->title);
        $this->assertSame([$old], Storage::disk('public')->allFiles('routes'));
    }

    public function test_delete_preserves_inquiry_snapshot_and_removes_only_its_own_image(): void
    {
        $path = UploadedFile::fake()->image('route.png')->store('routes', 'public');
        $unrelated = UploadedFile::fake()->image('other.png')->store('routes', 'public');
        $route = $this->makeRoute(['image_path' => $path]);
        DB::table('inquiries')->insert([
            'name' => 'Customer', 'phone' => '919876543210', 'service_type' => 'route', 'taxi_route_id' => $route->id,
            'service_title' => 'Bhuj to Mandvi', 'origin_name' => 'Bhuj', 'destination_name' => 'Mandvi', 'direction' => 'forward',
        ]);
        $this->signIn();
        $this->get('/admin/routes')->assertOk()->assertSee('data-route-delete-feedback', false)->assertSee('data-delete-route', false);
        $this->delete('/admin/routes/'.$route->id)->assertRedirect('/admin/routes');
        $this->assertDatabaseMissing('taxi_routes', ['id' => $route->id]);
        $this->assertDatabaseHas('inquiries', ['taxi_route_id' => null, 'service_title' => 'Bhuj to Mandvi', 'origin_name' => 'Bhuj']);
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($unrelated);
    }

    public function test_failed_delete_preserves_route_and_image(): void
    {
        $path = UploadedFile::fake()->image('route.png')->store('routes', 'public');
        $route = $this->makeRoute(['image_path' => $path]);
        TaxiRoute::deleting(fn (): bool => false);
        $this->withoutExceptionHandling();
        $this->signIn();
        try {
            $this->delete('/admin/routes/'.$route->id);
            $this->fail('Expected deletion failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Unable to delete the taxi route.', $exception->getMessage());
        }
        $this->assertDatabaseHas('taxi_routes', ['id' => $route->id]);
        Storage::disk('public')->assertExists($path);
    }

    public function test_missing_records_return_404_and_no_public_routes_are_added(): void
    {
        $this->signIn();
        $this->get('/admin/routes/999/edit')->assertNotFound();
        $this->put('/admin/routes/999', $this->routeData())->assertNotFound();
        $this->delete('/admin/routes/999')->assertNotFound();
        $this->get('/routes')->assertNotFound();
        $this->get('/routes/bhuj-to-mandvi')->assertNotFound();
    }
}
