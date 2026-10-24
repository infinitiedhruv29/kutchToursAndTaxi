<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_invoice_routes_require_authentication(): void
    {
        $this->get('/admin/invoices')->assertRedirect('/admin/login');
        $this->get('/admin/invoices/create')->assertRedirect('/admin/login');
        $this->get('/admin/invoices/data')->assertRedirect('/admin/login');
    }

    public function test_can_create_update_and_delete_invoice(): void
    {
        $user = User::factory()->create();

        $vType = VehicleType::create(['name' => 'Sedan', 'slug' => 'sedan']);
        $vCap = VehicleCapacity::create(['label' => '4 Passengers', 'passenger_capacity' => 4]);
        $vehicle = Vehicle::create([
            'name' => 'Swift Dzire',
            'slug' => 'swift-dzire',
            'vehicle_type_id' => $vType->id,
            'vehicle_capacity_id' => $vCap->id,
            'price_per_km' => 14.00,
            'is_published' => true,
        ]);

        Setting::create([
            'business_name' => 'Kutch Tours & Taxi',
            'contact_number' => '+91 99042 22293',
            'contact_email' => 'info@kutchtoursandtaxi.com',
            'address' => 'Station Road, Bhuj, Kutch, Gujarat',
            'gst_number' => '24ABCDE1234F1Z5',
        ]);

        // Create
        $response = $this->actingAs($user)->post('/admin/invoices', [
            'invoice_date' => '2026-10-23',
            'customer_name' => 'Rajesh Sharma',
            'customer_contact' => '+91 98765 43210',
            'customer_email' => 'rajesh@example.com',
            'customer_address' => '102 Sunrise Heights, Ahmedabad',
            'customer_gst_number' => '24XYZAB5678C1D9',
            'has_package' => 1,
            'package_name' => '3 Days Kutch Tour Package',
            'package_price' => 15000.00,
            'has_taxi' => 1,
            'vehicle_id' => $vehicle->id,
            'price_per_km' => 14.00,
            'total_distance_km' => 200.00, // 200 * 14 = 2800
            'item_descriptions' => ['Toll Tax & State Permit', 'Local Guide Fees'],
            'item_quantities' => [1, 2],
            'item_prices' => [500.00, 1000.00], // 500 + 2000 = 2500
            'tax_rate' => 5, // subtotal = 15000 + 2800 + 2500 = 20300. tax = 1015, total = 21315
            'notes' => 'Advance paid: Rs 5,000. Balance due upon completion.',
            'submit_action' => 'save',
        ]);

        $response->assertRedirect(route('admin.invoices.index'));
        $this->assertDatabaseHas('invoices', [
            'customer_name' => 'Rajesh Sharma',
            'customer_email' => 'rajesh@example.com',
            'subtotal' => 20300.00,
            'tax_rate' => 5.00,
            'tax_amount' => 1015.00,
            'total_amount' => 21315.00,
        ]);

        $invoice = Invoice::first();
        $this->assertCount(4, $invoice->items);
        $this->assertNotNull($invoice->business_details);
        $this->assertEquals('Kutch Tours & Taxi', $invoice->business_details['name']);

        // Check line items types
        $this->assertEquals(1, $invoice->items->where('item_type', 'package')->count());
        $this->assertEquals(1, $invoice->items->where('item_type', 'taxi')->count());
        $this->assertEquals(2, $invoice->items->where('item_type', 'other')->count());

        // Edit view
        $editResponse = $this->actingAs($user)->get('/admin/invoices/' . $invoice->id . '/edit');
        $editResponse->assertOk();
        $editResponse->assertSee('Rajesh Sharma');
        $editResponse->assertSee($invoice->invoice_number);

        // Update with 12% tax and updated items
        $updateResponse = $this->actingAs($user)->put('/admin/invoices/' . $invoice->id, [
            'invoice_date' => '2026-10-24',
            'customer_name' => 'Rajesh Sharma (Updated)',
            'customer_contact' => '+91 98765 43210',
            'has_package' => 0,
            'has_taxi' => 1,
            'vehicle_id' => $vehicle->id,
            'price_per_km' => 15.00,
            'total_distance_km' => 100.00, // 1500
            'item_descriptions' => ['Airport Parking'],
            'item_quantities' => [1],
            'item_prices' => [300.00], // 300
            'tax_rate' => 12, // subtotal = 1800, tax = 216, total = 2016
            'notes' => 'Updated invoice notes.',
            'submit_action' => 'save',
        ]);

        $updateResponse->assertRedirect(route('admin.invoices.index'));
        $invoice->refresh();
        $this->assertEquals('Rajesh Sharma (Updated)', $invoice->customer_name);
        $this->assertEquals('1800.00', (string) $invoice->subtotal);
        $this->assertEquals('12.00', (string) $invoice->tax_rate);
        $this->assertEquals('216.00', (string) $invoice->tax_amount);
        $this->assertEquals('2016.00', (string) $invoice->total_amount);
        $this->assertCount(2, $invoice->items);

        // DataTables JSON feed
        $dataResponse = $this->actingAs($user)->getJson('/admin/invoices/data');
        $dataResponse->assertOk();
        $dataResponse->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

        // Delete
        $deleteResponse = $this->actingAs($user)->delete('/admin/invoices/' . $invoice->id);
        $deleteResponse->assertRedirect(route('admin.invoices.index'));
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
        $this->assertEquals(0, \App\Models\InvoiceItem::where('invoice_id', $invoice->id)->count());
    }

    public function test_can_download_pdf_for_invoice(): void
    {
        $user = User::factory()->create();
        Setting::create([
            'business_name' => 'Kutch Tours & Taxi',
            'contact_number' => '+91 99042 22293',
            'contact_email' => 'info@kutchtoursandtaxi.com',
            'address' => 'Bhuj, Kutch',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-20261023-0001',
            'invoice_date' => '2026-10-23',
            'customer_name' => 'Pooja Dave',
            'customer_contact' => '+91 98250 11111',
            'subtotal' => 5000,
            'tax_rate' => 5,
            'tax_amount' => 250,
            'total_amount' => 5250,
            'business_details' => [
                'name' => 'Kutch Tours & Taxi',
                'contact' => '+91 99042 22293',
            ],
        ]);

        $invoice->items()->create([
            'item_type' => 'other',
            'description' => 'Custom sightseeing transfer',
            'quantity' => 1,
            'unit_price' => 5000,
            'amount' => 5000,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)->get('/admin/invoices/' . $invoice->id . '/pdf');
        $response->assertOk();
        $this->assertTrue(str_contains((string) $response->headers->get('content-type'), 'application/pdf'));
    }

    public function test_export_action_returns_redirect_url_for_ajax_request(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/admin/invoices', [
            'invoice_date' => '2026-10-23',
            'customer_name' => 'Sanjay Mehta',
            'submit_action' => 'export',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['message', 'redirect_url']);
        $this->assertTrue(str_contains($response->json('redirect_url'), '/pdf'));
    }

    public function test_validation_fails_for_invalid_invoice_input(): void
    {
        $user = User::factory()->create();

        // Missing customer_name and invalid phone
        $response = $this->actingAs($user)->post('/admin/invoices', [
            'invoice_date' => 'not-a-date',
            'customer_contact' => '123',
        ]);

        $response->assertSessionHasErrors(['customer_name', 'invoice_date', 'customer_contact']);
    }

    public function test_create_invoice_view_displays_custom_packages_in_dropdown(): void
    {
        $user = User::factory()->create();

        \App\Models\CustomPackage::create([
            'client_name' => 'Karan Patel',
            'client_phone' => '+91 98765 43210',
            'title' => '4 days in kutch',
            'duration' => '3N / 4D',
            'price_2p' => 1000.00,
        ]);

        $response = $this->actingAs($user)->get('/admin/invoices/create');
        $response->assertOk();
        $response->assertSee('4 days in kutch');
        $response->assertSee('Custom Proposals (Package Builder)');
    }
}
