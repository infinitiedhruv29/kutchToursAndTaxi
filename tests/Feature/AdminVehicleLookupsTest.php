<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleCapacity;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminVehicleLookupsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function modules(): array
    {
        return [
            ['path' => '/admin/vehicle-types', 'model' => VehicleType::class, 'field' => 'name', 'data' => ['name' => 'SUV', 'sort_order' => 0]],
            ['path' => '/admin/vehicle-capacities', 'model' => VehicleCapacity::class, 'field' => 'label', 'data' => ['label' => '6 passengers', 'passenger_capacity' => 6, 'sort_order' => 0]],
        ];
    }

    public function test_all_lookup_endpoints_require_authentication(): void
    {
        foreach ($this->modules() as $module) {
            $record = $module['model']::create($module['data']);
            foreach (['', '/data', '/create', '/'.$record->id.'/edit'] as $suffix) {
                $this->get($module['path'].$suffix)->assertRedirect('/admin/login');
            }
            $this->postJson($module['path'], $module['data'])->assertUnauthorized();
            $this->putJson($module['path'].'/'.$record->id, $module['data'])->assertUnauthorized();
            $this->deleteJson($module['path'].'/'.$record->id)->assertUnauthorized();
        }
    }

    public function test_both_lookup_modules_render_create_update_and_delete(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ($this->modules() as $module) {
            $this->get($module['path'])->assertOk()->assertSee('data-admin-datatable', false);
            $this->get($module['path'].'/create')->assertOk()->assertSee('data-admin-form', false);
            $this->getJson($module['path'].'/data')->assertJsonPath('recordsTotal', 0)->assertJsonCount(0, 'data');
            $this->postJson($module['path'], $module['data'] + ['id' => 999, 'image_path' => 'forged'])->assertOk();
            $record = $module['model']::firstOrFail();
            $this->assertNotSame(999, $record->id);
            $this->get($module['path'].'/'.$record->id.'/edit')->assertOk();
            $this->put($module['path'].'/'.$record->id, array_replace($module['data'], [$module['field'] => 'Updated', 'sort_order' => 8]))
                ->assertRedirect($module['path'])->assertSessionHas('status');
            $this->assertSame('Updated', $record->fresh()->getAttribute($module['field']));
            $this->assertSame(8, $record->fresh()->sort_order);
            $this->deleteJson($module['path'].'/'.$record->id)->assertOk();
            $this->assertSame(0, $module['model']::count());
            $this->get($module['path'].'/999/edit')->assertNotFound();
            $this->putJson($module['path'].'/999', $module['data'])->assertNotFound();
            $this->deleteJson($module['path'].'/999')->assertNotFound();
        }
    }

    public function test_trimmed_case_insensitive_unique_names_and_labels(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ($this->modules() as $module) {
            $this->postJson($module['path'], array_replace($module['data'], [$module['field'] => '  Custom Choice  ']))->assertOk();
            $record = $module['model']::firstOrFail();
            $this->assertSame('Custom Choice', $record->getAttribute($module['field']));
            $this->postJson($module['path'], array_replace($module['data'], [$module['field'] => ' custom choice ']))
                ->assertUnprocessable()->assertJsonValidationErrors($module['field']);
            $this->putJson($module['path'].'/'.$record->id, array_replace($module['data'], [$module['field'] => 'CUSTOM CHOICE']))->assertOk();
            $other = $module['model']::create(array_replace($module['data'], [$module['field'] => 'Other']));
            $this->putJson($module['path'].'/'.$other->id, array_replace($module['data'], [$module['field'] => 'custom choice']))->assertUnprocessable();
        }
    }

    public function test_required_fields_counts_and_order_are_validated_and_equal_counts_are_allowed(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ($this->modules() as $module) {
            $this->postJson($module['path'], [])->assertUnprocessable()->assertJsonValidationErrors([$module['field'], 'sort_order']);
            foreach ([-1, '1.2', 4294967296] as $order) {
                $this->postJson($module['path'], array_replace($module['data'], ['sort_order' => $order]))->assertUnprocessable();
            }
            $this->postJson($module['path'], array_replace($module['data'], [$module['field'] => ['invalid']]))->assertUnprocessable();
        }
        foreach ([0, -1, '2.5', 65536] as $count) {
            $this->postJson('/admin/vehicle-capacities', ['label' => 'Invalid', 'passenger_capacity' => $count, 'sort_order' => 0])->assertUnprocessable()->assertJsonValidationErrors('passenger_capacity');
        }
        $this->postJson('/admin/vehicle-capacities', ['label' => '4 passengers', 'passenger_capacity' => 4, 'sort_order' => 0])->assertOk();
        $this->postJson('/admin/vehicle-capacities', ['label' => '4-seater premium', 'passenger_capacity' => 4, 'sort_order' => 0])->assertOk();
        $this->assertDatabaseCount('vehicle_capacities', 2);
    }

    public function test_used_lookup_deletion_is_blocked_for_drafts_and_database_foreign_keys_remain_active(): void
    {
        $this->actingAs(User::factory()->create());
        $type = VehicleType::create(['name' => 'SUV', 'sort_order' => 0]);
        $capacity = VehicleCapacity::create(['label' => '6 passengers', 'passenger_capacity' => 6, 'sort_order' => 0]);
        $vehicle = Vehicle::create(['name' => 'Draft vehicle', 'slug' => 'draft', 'vehicle_type_id' => $type->id, 'vehicle_capacity_id' => $capacity->id]);
        foreach ([['/admin/vehicle-types', $type], ['/admin/vehicle-capacities', $capacity]] as [$path, $record]) {
            $this->deleteJson($path.'/'.$record->id)->assertStatus(409)->assertJsonStructure(['message']);
            $this->delete($path.'/'.$record->id)->assertRedirect($path)->assertSessionHas('error');
            $this->get($path)->assertOk()->assertSee('data-kind="error"', false);
            $this->assertNotNull($record->fresh());
            try {
                $record->delete();
                $this->fail('Expected foreign-key protection.');
            } catch (QueryException $exception) {
                $this->assertStringContainsString('FOREIGN KEY', $exception->getMessage());
            }
        }
        $vehicle->delete();
        $this->deleteJson('/admin/vehicle-types/'.$type->id)->assertOk();
        $this->deleteJson('/admin/vehicle-capacities/'.$capacity->id)->assertOk();
    }

    public function test_foreign_key_race_is_reported_as_a_friendly_conflict(): void
    {
        $this->actingAs(User::factory()->create());
        $type = VehicleType::create(['name' => 'SUV', 'sort_order' => 0]);
        $capacity = VehicleCapacity::create(['label' => '6 passengers', 'passenger_capacity' => 6, 'sort_order' => 0]);
        VehicleType::deleting(function (VehicleType $record) use ($capacity): void {
            Vehicle::create(['name' => 'Concurrent vehicle', 'slug' => 'concurrent', 'vehicle_type_id' => $record->id, 'vehicle_capacity_id' => $capacity->id]);
        });
        $this->deleteJson('/admin/vehicle-types/'.$type->id)->assertStatus(409);
        $this->assertDatabaseHas('vehicle_types', ['id' => $type->id]);
    }

    public function test_lookup_search_sort_pagination_and_counts(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ($this->modules() as $module) {
            for ($index = 0; $index < 18; $index++) {
                $module['model']::create(array_replace($module['data'], [$module['field'] => sprintf('Choice %02d', $index), 'sort_order' => 1]));
            }
            $priority = $module['model']::create(array_replace($module['data'], [$module['field'] => 'Priority', 'sort_order' => 0]));
            $this->getJson($module['path'].'/data')->assertOk()->assertJsonPath('recordsTotal', 19)->assertJsonCount(15, 'data')->assertJsonPath('data.0.id', $priority->id);
            $this->getJson($module['path'].'/data?start=15')->assertJsonCount(4, 'data');
            $this->getJson($module['path'].'/data?q=Choice')->assertJsonPath('recordsFiltered', 18);
            $this->get($module['path'].'?page=2')->assertOk()->assertViewHas('records', fn ($rows): bool => $rows->count() === 4);
            $this->getJson($module['path'].'/data?sort='.$module['field'].'&direction=desc')->assertJsonPath('data.0.id', $priority->id);
            $this->getJson($module['path'].'/data?sort=vehicles_count')->assertUnprocessable();
            $this->getJson($module['path'].'/data?length=999')->assertUnprocessable();
        }
        $this->getJson('/admin/vehicle-capacities/data?q=6')->assertOk()->assertJsonPath('recordsFiltered', 19);
        $type = VehicleType::firstOrFail();
        $capacity = VehicleCapacity::firstOrFail();
        Vehicle::create(['name' => 'Counter', 'slug' => 'counter', 'vehicle_type_id' => $type->id, 'vehicle_capacity_id' => $capacity->id]);
        $count = $this->getJson('/admin/vehicle-types/data?q=Choice%2000')->assertOk()->json('data.0.vehicles_count');
        $this->assertSame('1', trim($count));
    }

    public function test_lookup_return_state_and_escaped_cells(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ($this->modules() as $module) {
            $destination = url($module['path']).'?q=Choice&sort='.$module['field'].'&direction=desc&page=2&per_page=25';
            $this->get($module['path'].'/create?'.http_build_query(['return_to' => $destination]))->assertViewHas('returnTo', $destination);
            $this->postJson($module['path'], array_replace($module['data'], [$module['field'] => '<script>Choice</script>', 'return_to' => $destination]))->assertOk()->assertJsonPath('redirect_url', $destination);
            $record = $module['model']::firstOrFail();
            $this->get($module['path'].'/'.$record->id.'/edit')->assertOk();
            $cell = $this->getJson($module['path'].'/data')->assertOk()->json('data.0.'.$module['field']);
            $this->assertStringNotContainsString('<script>', $cell);
            $this->assertStringContainsString('&lt;script&gt;', $cell);
            $this->putJson($module['path'].'/'.$record->id, $module['data'] + ['return_to' => 'https://evil.test'.$module['path']])->assertOk()->assertJsonPath('redirect_url', url($module['path']));
        }
    }
}
