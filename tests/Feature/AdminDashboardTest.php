<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_dashboard_has_real_zero_counts_and_empty_state(): void
    {
        $this->withoutVite();
        $response = $this->actingAs(User::factory()->create())->get('/admin');
        $response->assertOk()->assertSee('No inquiries yet')
            ->assertViewHas('newInquiries', 0)
            ->assertViewHas('counts', fn ($counts) => count($counts) === 9 && array_sum($counts) === 0);
    }

    public function test_counts_and_latest_five_inquiries_use_database_records(): void
    {
        $this->withoutVite();
        DB::table('taxi_routes')->insert([
            'title' => 'Bhuj to Mandvi', 'slug' => 'bhuj-to-mandvi', 'origin_name' => 'Bhuj', 'destination_name' => 'Mandvi',
        ]);
        DB::table('packages')->insert(['title' => 'Test package', 'slug' => 'test-package']);
        for ($index = 1; $index <= 6; $index++) {
            DB::table('inquiries')->insert([
                'name' => 'Customer '.$index, 'phone' => '9900000000', 'service_type' => 'route',
                'service_title' => 'Bhuj to Mandvi', 'status' => $index === 1 ? 'closed' : 'new',
                'created_at' => now()->addMinutes($index), 'updated_at' => now(),
            ]);
        }
        $this->actingAs(User::factory()->create())->get('/admin')
            ->assertOk()->assertSee('Customer 6')->assertDontSee('Customer 1')
            ->assertViewHas('newInquiries', 5)
            ->assertViewHas('counts', fn ($counts) => $counts['Routes'] === 1 && $counts['Packages'] === 1 && $counts['Inquiries'] === 6)
            ->assertViewHas('recentInquiries', fn ($items) => $items->count() === 5 && $items->first()->name === 'Customer 6');
    }
}
