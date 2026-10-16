<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleCapacity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class AdminVehiclesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    private function data(array $overrides = []): array
    {
        $type = VehicleType::first() ?? VehicleType::create(['name' => 'SUV', 'sort_order' => 0]);
        $capacity = VehicleCapacity::first() ?? VehicleCapacity::create(['label' => '6 passengers', 'passenger_capacity' => 6, 'sort_order' => 0]);

        return array_replace(['name' => 'Innova or similar', 'slug' => 'innova', 'vehicle_type_id' => $type->id, 'vehicle_capacity_id' => $capacity->id, 'sort_order' => 0], $overrides);
    }

    public function test_all_vehicle_endpoints_are_protected_and_public_endpoints_do_not_exist(): void
    {
        $vehicle = Vehicle::create($this->data());
        foreach (['/admin/vehicles', '/admin/vehicles/data', '/admin/vehicles/create', '/admin/vehicles/'.$vehicle->id.'/edit'] as $path) {
            $this->get($path)->assertRedirect('/admin/login');
        }
        $this->post('/admin/vehicles', $this->data())->assertRedirect('/admin/login');
        $this->put('/admin/vehicles/'.$vehicle->id, $this->data())->assertRedirect('/admin/login');
        $this->deleteJson('/admin/vehicles/'.$vehicle->id)->assertUnauthorized();
        $this->get('/vehicles')->assertNotFound();
        $this->get('/vehicles/innova')->assertNotFound();
    }

    public function test_empty_setup_guidance_forms_and_shared_listing_render(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/admin/vehicles')->assertOk()->assertSee('No vehicles found')->assertSee('data-admin-datatable', false);
        $this->get('/admin/vehicles/create')->assertOk()->assertSee('Set up vehicle types and passenger capacities first.')
            ->assertSee('data-vehicle-form', false)->assertSee('data-image-upload', false);
        $this->assertDatabaseCount('vehicle_types', 0);
        $this->assertDatabaseCount('vehicle_capacities', 0);
        $this->data();
        $this->get('/admin/vehicles/create')->assertOk()->assertDontSee('Set up vehicle types and passenger capacities first.');
        $this->getJson('/admin/vehicles/data')->assertExactJson(['draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
    }

    public function test_create_update_and_native_redirects_use_only_validated_fields(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/vehicles', $this->data(['image_path' => 'forged.webp', 'id' => 900, 'is_featured' => true]))
            ->assertOk()->assertJsonPath('redirect_url', route('admin.vehicles.index'));
        $vehicle = Vehicle::firstOrFail();
        $this->assertNotSame(900, $vehicle->id);
        $this->assertNull($vehicle->image_path);
        $this->assertFalse($vehicle->is_published);
        $this->assertNull($vehicle->price_per_km);
        $this->assertNull($vehicle->min_km_per_day);
        $this->put('/admin/vehicles/'.$vehicle->id, $this->data(['name' => 'Updated', 'is_published' => true, 'sort_order' => 3, 'price_per_km' => '14.50', 'min_km_per_day' => 250, 'notes' => '<script>plain text</script>']))
            ->assertRedirect('/admin/vehicles')->assertSessionHas('status');
        $vehicle->refresh();
        $this->assertTrue($vehicle->is_published);
        $this->assertSame(3, $vehicle->sort_order);
        $this->assertSame('14.50', $vehicle->price_per_km);
        $this->assertSame(250, $vehicle->min_km_per_day);
        $this->assertSame('<script>plain text</script>', $vehicle->notes);
        $this->get('/admin/vehicles/'.$vehicle->id.'/edit')->assertOk()->assertSee('&lt;script&gt;plain text&lt;/script&gt;', false);
        $this->putJson('/admin/vehicles/'.$vehicle->id, $this->data())->assertOk();
        $this->assertFalse($vehicle->fresh()->is_published);
        $this->assertNull($vehicle->fresh()->price_per_km);
    }

    public function test_slug_suggestions_manual_duplicates_and_renaming(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/vehicles', $this->data(['slug' => null, 'auto_slug' => true]))->assertOk();
        $first = Vehicle::firstOrFail();
        $this->assertSame('innova-or-similar', $first->slug);
        $this->postJson('/admin/vehicles', $this->data(['slug' => null, 'auto_slug' => true]))->assertOk();
        $this->assertDatabaseHas('vehicles', ['slug' => 'innova-or-similar-2']);
        $this->postJson('/admin/vehicles', $this->data(['slug' => $first->slug]))->assertUnprocessable()->assertJsonValidationErrors('slug');
        $this->putJson('/admin/vehicles/'.$first->id, $this->data(['name' => 'Renamed', 'slug' => $first->slug, 'auto_slug' => true]))->assertOk();
        $this->assertSame('innova-or-similar', $first->fresh()->slug);
        $this->putJson('/admin/vehicles/'.$first->id, $this->data(['slug' => 'manual-change']))->assertOk();
        $this->assertSame('manual-change', $first->fresh()->slug);
    }

    public function test_slug_collision_during_insert_retries_automatically(): void
    {
        $this->actingAs(User::factory()->create());
        $existing = Vehicle::create($this->data());
        $attempts = 0;
        Vehicle::creating(function (Vehicle $vehicle) use (&$attempts, $existing): void {
            if ($attempts++ === 0) {
                $vehicle->slug = $existing->slug;
            }
        });
        $this->postJson('/admin/vehicles', $this->data(['auto_slug' => true]))->assertOk();
        $this->assertDatabaseCount('vehicles', 2);
        $this->assertSame(2, $attempts);
    }

    public function test_required_fields_relationships_and_numeric_ranges_are_validated(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/vehicles', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'slug', 'vehicle_type_id', 'vehicle_capacity_id', 'sort_order']);
        foreach ([
            ['vehicle_type_id' => 999, 'vehicle_capacity_id' => 999], ['slug' => 'Bad Slug'],
            ['price_per_km' => '-1'], ['price_per_km' => '1.001'], ['price_per_km' => '1e3'], ['price_per_km' => '10000000000'],
            ['min_km_per_day' => -1], ['min_km_per_day' => '1.5'], ['min_km_per_day' => 4294967296],
            ['sort_order' => -1], ['is_published' => 'bad'], ['notes' => str_repeat('x', 5001)],
        ] as $invalid) {
            $this->postJson('/admin/vehicles', $this->data($invalid))->assertUnprocessable();
        }
        $this->assertDatabaseCount('vehicles', 0);
    }

    public function test_optional_prices_allow_publishing_and_zero_remains_explicit(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/vehicles', $this->data(['is_published' => true]))->assertOk();
        $vehicle = Vehicle::firstOrFail();
        $cell = $this->getJson('/admin/vehicles/data')->assertOk()->json('data.0.pricing');
        $this->assertStringContainsString('Price on inquiry', $cell);
        $this->assertStringNotContainsString('km/day', $cell);
        $this->putJson('/admin/vehicles/'.$vehicle->id, $this->data(['price_per_km' => '0', 'min_km_per_day' => '0']))->assertOk();
        $this->assertSame('0.00', $vehicle->fresh()->price_per_km);
        $cell = $this->getJson('/admin/vehicles/data')->json('data.0.pricing');
        $this->assertStringContainsString('₹0.00/km', $cell);
        $this->assertStringContainsString('Minimum 0 km/day', $cell);
    }

    public function test_search_combined_filters_order_and_pagination(): void
    {
        $this->actingAs(User::factory()->create());
        $base = $this->data();
        for ($index = 0; $index < 18; $index++) {
            Vehicle::create(array_replace($base, ['name' => sprintf('Taxi %02d', $index), 'slug' => 'taxi-'.$index, 'is_published' => true, 'sort_order' => 2, 'price_per_km' => $index]));
        }
        $priority = Vehicle::create(array_replace($base, ['slug' => 'priority', 'name' => 'Priority', 'is_published' => true]));
        Vehicle::create(array_replace($base, ['slug' => 'draft', 'name' => 'Draft']));
        $parameters = ['status' => 'published', 'vehicle_type_id' => $base['vehicle_type_id'], 'vehicle_capacity_id' => $base['vehicle_capacity_id']];
        $response = $this->getJson('/admin/vehicles/data?'.http_build_query($parameters))->assertOk()
            ->assertJsonPath('recordsTotal', 20)->assertJsonPath('recordsFiltered', 19)->assertJsonCount(15, 'data')->assertJsonPath('data.0.id', $priority->id);
        $this->assertStringContainsString('SUV', $response->json('data.0.type'));
        $this->getJson('/admin/vehicles/data?'.http_build_query($parameters + ['start' => 15]))->assertJsonCount(4, 'data');
        foreach (['SUV', '6 passengers', 'Taxi'] as $search) {
            $this->getJson('/admin/vehicles/data?'.http_build_query($parameters + ['search' => ['value' => $search]]))->assertOk()->assertJsonPath('recordsFiltered', $search === 'Taxi' ? 18 : 19);
        }
        $this->get('/admin/vehicles?'.http_build_query($parameters + ['page' => 2]))->assertOk()->assertViewHas('vehicles', fn ($rows): bool => $rows->count() === 4);
        $this->getJson('/admin/vehicles/data?'.http_build_query(['q' => 'Taxi', 'order' => [['column' => 3, 'dir' => 'desc']]]))->assertOk()->assertJsonPath('data.0.id', 18);
        $this->getJson('/admin/vehicles/data?'.http_build_query(['order' => [['column' => 1, 'dir' => 'asc']]]))->assertUnprocessable();
        $this->getJson('/admin/vehicles/data?sort=password')->assertUnprocessable();
        $this->getJson('/admin/vehicles/data?length=-1')->assertUnprocessable();
    }

    public function test_listing_state_is_preserved_only_for_valid_module_destinations(): void
    {
        $this->actingAs(User::factory()->create());
        $data = $this->data();
        $destination = route('admin.vehicles.index').'?'.http_build_query(['q' => 'Taxi', 'status' => 'draft', 'vehicle_type_id' => $data['vehicle_type_id'], 'vehicle_capacity_id' => $data['vehicle_capacity_id'], 'sort' => 'name', 'direction' => 'desc', 'page' => 2, 'per_page' => 25]);
        $this->get('/admin/vehicles/create?'.http_build_query(['return_to' => $destination]))->assertOk()->assertViewHas('returnTo', $destination);
        $this->postJson('/admin/vehicles', $data + ['return_to' => $destination])->assertOk()->assertJsonPath('redirect_url', $destination);
        $vehicle = Vehicle::firstOrFail();
        foreach (['https://evil.test/admin/vehicles', '/admin/routes', '/admin/vehicles?sort=password'] as $hostile) {
            $this->putJson('/admin/vehicles/'.$vehicle->id, $data + ['return_to' => $hostile])->assertOk()->assertJsonPath('redirect_url', route('admin.vehicles.index'));
        }
    }

    public function test_images_replace_remove_validate_and_use_same_origin_urls(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/admin/vehicles', $this->data(['image' => UploadedFile::fake()->image('first.webp')]))->assertRedirect('/admin/vehicles');
        $vehicle = Vehicle::firstOrFail();
        $first = $vehicle->image_path;
        Storage::disk('public')->assertExists($first);
        $this->get('/admin/vehicles/'.$vehicle->id.'/edit')->assertOk()->assertSee('src="/storage/'.$first.'"', false);
        $this->getJson('/admin/vehicles/data')->assertOk();
        $this->put('/admin/vehicles/'.$vehicle->id, $this->data(['image' => UploadedFile::fake()->image('replacement.png'), 'remove_image' => true]))->assertRedirect('/admin/vehicles');
        $second = $vehicle->fresh()->image_path;
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);
        $this->putJson('/admin/vehicles/'.$vehicle->id, $this->data(['remove_image' => true]))->assertOk();
        Storage::disk('public')->assertMissing($second);
        $this->assertNull($vehicle->fresh()->image_path);
        foreach ([UploadedFile::fake()->create('bad.svg', 2, 'image/svg+xml'), UploadedFile::fake()->image('huge.png')->size(2049)] as $image) {
            $this->post('/admin/vehicles', $this->data(['slug' => 'new-image', 'image' => $image]))->assertSessionHasErrors('image');
        }
    }

    public function test_failed_save_cleans_upload_without_removing_saved_image(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('old.jpg')->store('vehicles', 'public');
        $vehicle = Vehicle::create($this->data(['image_path' => $path]));
        Vehicle::saving(fn () => throw new RuntimeException('Save failed'));
        $this->withoutExceptionHandling();
        try {
            $this->put('/admin/vehicles/'.$vehicle->id, $this->data(['name' => 'Must not save', 'image' => UploadedFile::fake()->image('new.jpg')]));
            $this->fail('Expected save failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Save failed', $exception->getMessage());
        }
        $this->assertSame('Innova or similar', $vehicle->fresh()->name);
        $this->assertSame([$path], Storage::disk('public')->allFiles('vehicles'));
    }

    public function test_delete_preserves_inquiries_and_removes_only_own_image(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('vehicle.jpg')->store('vehicles', 'public');
        $other = UploadedFile::fake()->image('other.jpg')->store('vehicles', 'public');
        $vehicle = Vehicle::create($this->data(['image_path' => $path]));
        DB::table('inquiries')->insert(['name' => 'Customer', 'phone' => '123', 'service_type' => 'taxi', 'service_title' => 'Original request']);
        $this->deleteJson('/admin/vehicles/'.$vehicle->id)->assertOk();
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($other);
        $this->assertDatabaseHas('inquiries', ['service_title' => 'Original request']);
        $this->assertDatabaseCount('vehicles', 0);
        $this->delete('/admin/vehicles/999')->assertNotFound();
        $this->get('/admin/vehicles/999/edit')->assertNotFound();
        $this->putJson('/admin/vehicles/999', $this->data())->assertNotFound();
    }

    public function test_cell_content_is_escaped_and_relationships_are_eager_loaded(): void
    {
        $this->actingAs(User::factory()->create());
        Vehicle::create($this->data(['name' => '<script>unsafe</script>', 'image_alt' => '" onerror="bad', 'image_path' => 'vehicles/image.webp']));
        DB::enableQueryLog();
        $response = $this->getJson('/admin/vehicles/data')->assertOk();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();
        $this->assertStringNotContainsString('<script>', $response->json('data.0.vehicle'));
        $this->assertStringContainsString('&lt;script&gt;', $response->json('data.0.vehicle'));
        $this->assertStringContainsString('&quot; onerror=&quot;', $response->json('data.0.vehicle'));
        $relationshipReads = array_filter($queries, fn (array $query): bool => str_starts_with($query['query'], 'select') && (str_contains($query['query'], 'from "vehicle_types"') || str_contains($query['query'], 'from "vehicle_capacities"')));
        $this->assertCount(2, $relationshipReads);
    }

    public function test_manual_slug_collision_during_save_cleans_new_upload(): void
    {
        $this->actingAs(User::factory()->create());
        $existing = Vehicle::create($this->data());
        Vehicle::creating(function (Vehicle $vehicle) use ($existing): void {
            $vehicle->slug = $existing->slug;
        });
        $this->post('/admin/vehicles', $this->data(['slug' => 'different-slug', 'image' => UploadedFile::fake()->image('new.png')]))->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('vehicles', 1);
        $this->assertSame([], Storage::disk('public')->allFiles('vehicles'));
    }

    public function test_failed_vehicle_deletion_preserves_the_record_and_image(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('old.jpg')->store('vehicles', 'public');
        $vehicle = Vehicle::create($this->data(['image_path' => $path]));
        Vehicle::deleting(fn (): bool => false);
        $this->withoutExceptionHandling();
        try {
            $this->delete('/admin/vehicles/'.$vehicle->id);
            $this->fail('Expected delete failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Unable to delete the vehicle.', $exception->getMessage());
        }
        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id]);
        Storage::disk('public')->assertExists($path);
    }
}
