<?php

namespace Tests\Feature;

use App\Models\TaxiRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRouteDatatableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    private function createRoute(array $attributes = []): TaxiRoute
    {
        return TaxiRoute::create(array_replace([
            'title' => 'Route', 'origin_name' => 'Bhuj', 'destination_name' => 'Mandvi',
            'slug' => 'route-'.TaxiRoute::count(),
        ], $attributes));
    }

    private function dataUrl(array $parameters = []): string
    {
        return '/admin/routes/data?'.http_build_query($parameters);
    }

    public function test_data_endpoint_requires_authentication_and_has_a_named_route(): void
    {
        $this->get('/admin/routes/data')->assertRedirect('/admin/login');
        $this->getJson('/admin/routes/data')->assertUnauthorized();
        $this->assertStringEndsWith('/admin/routes/data', route('admin.routes.data'));
    }

    public function test_shared_component_and_server_rendered_fallback_are_present(): void
    {
        $this->actingAs(User::factory()->create());
        $this->createRoute(['title' => 'Visible fallback route']);
        $this->get('/admin/routes')->assertOk()->assertSee('data-admin-datatable', false)
            ->assertSee('data-table-fallback', false)->assertSee('Visible fallback route')
            ->assertSee('data-table-retry', false)->assertSee('data-table-sort="0"', false)
            ->assertSee('data-table-sort="4"', false)->assertDontSee('data-table-sort="1"', false);
        $this->get('/admin')->assertOk()->assertDontSee('data-admin-datatable', false);
    }

    public function test_empty_response_uses_integer_counts_and_echoes_validated_draw(): void
    {
        $this->actingAs(User::factory()->create());
        $this->getJson($this->dataUrl(['draw' => '7']))->assertOk()
            ->assertExactJson(['draw' => 7, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
    }

    public function test_search_filters_sorting_and_pagination_use_database_records(): void
    {
        $this->actingAs(User::factory()->create());
        $first = $this->createRoute(['title' => 'Priority', 'sort_order' => 0, 'is_published' => true]);
        for ($index = 0; $index < 17; $index++) {
            $this->createRoute(['title' => 'Published '.$index, 'sort_order' => 2, 'is_published' => true]);
        }
        $draft = $this->createRoute(['title' => 'Draft zero 0', 'origin_name' => 'Anjar', 'destination_name' => 'Ahmedabad']);
        $this->getJson($this->dataUrl(['draw' => 1, 'status' => 'published']))->assertOk()
            ->assertJsonPath('recordsTotal', 19)->assertJsonPath('recordsFiltered', 18)
            ->assertJsonCount(15, 'data')->assertJsonPath('data.0.id', $first->id);
        $this->getJson($this->dataUrl(['status' => 'published', 'start' => 15]))->assertOk()->assertJsonCount(3, 'data');
        foreach (['0', 'Anjar', 'Ahmedabad'] as $search) {
            $this->getJson($this->dataUrl(['search' => ['value' => $search, 'regex' => 'false'], 'status' => 'draft']))
                ->assertOk()->assertJsonPath('recordsFiltered', 1)->assertJsonPath('data.0.id', $draft->id);
        }
        $this->getJson($this->dataUrl(['status' => 'published', 'length' => 25]))->assertJsonCount(18, 'data');
        $this->getJson($this->dataUrl(['search' => ['value' => 'no match']]))->assertJsonPath('recordsFiltered', 0)->assertJsonCount(0, 'data');
    }

    public function test_only_server_allowlisted_columns_control_sorting_with_stable_ties(): void
    {
        $this->actingAs(User::factory()->create());
        $older = $this->createRoute(['title' => 'Alpha', 'sort_order' => 3]);
        $newer = $this->createRoute(['title' => 'Alpha', 'sort_order' => 3]);
        $zulu = $this->createRoute(['title' => 'Zulu', 'sort_order' => 0]);
        $this->getJson($this->dataUrl())->assertJsonPath('data.0.id', $zulu->id)->assertJsonPath('data.1.id', $newer->id);
        $this->getJson($this->dataUrl(['order' => [['column' => 0, 'dir' => 'asc']], 'columns' => [['name' => 'password', 'data' => 'DROP TABLE users']]]))
            ->assertOk()->assertJsonPath('data.0.id', $newer->id)->assertJsonPath('data.1.id', $older->id);
        $this->getJson($this->dataUrl(['order' => [['column' => 4, 'dir' => 'desc']]]))->assertJsonPath('data.0.id', $newer->id);
        $this->getJson($this->dataUrl(['order' => [['column' => 0, 'dir' => 'desc']]]))->assertJsonPath('data.0.id', $zulu->id);
    }

    public function test_invalid_and_hostile_parameters_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ([
            ['length' => -1], ['length' => 1000], ['start' => -1], ['start' => 2147483647],
            ['draw' => '<script>bad</script>'], ['search' => ['value' => str_repeat('x', 256)]],
            ['search' => ['regex' => true]], ['status' => 'invalid'],
            ['order' => [['column' => 1, 'dir' => 'asc']]], ['order' => [['column' => 100, 'dir' => 'asc']]],
            ['order' => [['column' => 0, 'dir' => 'asc; DROP TABLE taxi_routes']]],
            ['columns' => [['search' => ['value' => 'unsupported']]]],
            ['columns' => [['search' => ['regex' => true]]]],
            ['sort' => 'password'], ['direction' => 'sideways'], ['per_page' => 10], ['page' => 0],
        ] as $parameters) {
            $this->getJson($this->dataUrl($parameters))->assertUnprocessable()->assertJsonStructure(['errors']);
        }
        $this->assertDatabaseCount('users', 1);
    }

    public function test_rendered_cells_escape_content_and_keep_storage_urls_same_origin(): void
    {
        $this->actingAs(User::factory()->create());
        $route = $this->createRoute([
            'title' => '<script>alert(1)</script>', 'origin_name' => '<img src=x onerror=alert(1)>',
            'image_path' => 'routes/test.webp', 'image_alt' => '" onerror="alert(1)',
            'forward_one_way_fare' => 0, 'reverse_enabled' => true, 'is_featured' => true,
        ]);
        $response = $this->getJson($this->dataUrl())->assertOk()->assertJsonPath('data.0.id', $route->id);
        $cell = $response->json('data.0.route');
        $this->assertStringContainsString('&lt;script&gt;', $cell);
        $this->assertStringNotContainsString('<script>', $cell);
        $this->assertStringNotContainsString('<img src=x', $cell);
        $this->assertStringContainsString('src="/storage/routes/test.webp"', $cell);
        $this->assertStringContainsString('&quot; onerror=&quot;', $cell);
        $this->assertStringContainsString('₹0.00', $response->json('data.0.fares'));
        $this->assertStringContainsString('Price on inquiry', $response->json('data.0.fares'));
        $this->assertStringContainsString('Enabled', $response->json('data.0.reverse'));
        $this->assertStringContainsString('Featured', $response->json('data.0.visibility'));
        $this->assertStringNotContainsString('data-title="<script>', $response->json('data.0.actions'));
    }

    public function test_url_parameters_restore_the_server_rendered_listing(): void
    {
        $this->actingAs(User::factory()->create());
        for ($index = 0; $index < 28; $index++) {
            $this->createRoute(['title' => sprintf('Route %02d', $index)]);
        }
        $this->get('/admin/routes?q=Route&status=draft&sort=title&direction=desc&per_page=25&page=2')->assertOk()
            ->assertViewHas('routes', fn ($routes): bool => $routes->count() === 3 && $routes->first()->title === 'Route 02');
        $this->get('/admin/routes?sort=invalid')->assertSessionHasErrors('sort');
    }

    public function test_json_deletion_cleans_image_preserves_snapshots_and_returns_json(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('route.png')->store('routes', 'public');
        $route = $this->createRoute(['image_path' => $path]);
        DB::table('inquiries')->insert(['name' => 'Customer', 'phone' => '12345678', 'service_type' => 'route', 'taxi_route_id' => $route->id, 'service_title' => 'Original route']);
        $this->deleteJson('/admin/routes/'.$route->id)->assertOk()->assertJsonStructure(['message']);
        $this->assertDatabaseMissing('taxi_routes', ['id' => $route->id]);
        $this->assertDatabaseHas('inquiries', ['taxi_route_id' => null, 'service_title' => 'Original route']);
        Storage::disk('public')->assertMissing($path);
        $this->deleteJson('/admin/routes/'.$route->id)->assertNotFound();
    }
}
