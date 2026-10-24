<?php

namespace App\Http\Requests\Admin;

use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'invoice_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_contact' => ['nullable', 'string', 'regex:/^\+?[0-9\s\-()]{10,20}$/'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'customer_gst_number' => ['nullable', 'string', 'max:30'],
            'has_package' => ['sometimes', 'boolean'],
            'package_name' => ['nullable', 'string', 'max:255'],
            'package_price' => ['nullable', 'numeric', 'min:0'],
            'has_taxi' => ['sometimes', 'boolean'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'price_per_km' => ['nullable', 'numeric', 'min:0'],
            'total_distance_km' => ['nullable', 'numeric', 'min:0'],
            'item_descriptions' => ['nullable', 'array'],
            'item_descriptions.*' => ['nullable', 'string', 'max:255'],
            'item_quantities' => ['nullable', 'array'],
            'item_quantities.*' => ['nullable', 'numeric', 'min:0'],
            'item_prices' => ['nullable', 'array'],
            'item_prices.*' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', Rule::in([0, 5, 12, 18])],
            'notes' => ['nullable', 'string', 'max:2000'],
            'submit_action' => ['nullable', 'string', 'in:save,export'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_contact.regex' => 'The customer contact number must be a valid phone number (10 to 20 digits, spaces, dashes, or parentheses allowed).',
        ];
    }

    public function computedItemsData(): array
    {
        $items = [];
        $order = 1;

        // Package item
        if ($this->boolean('has_package') && $this->filled('package_name')) {
            $price = (float) $this->input('package_price', 0);
            $items[] = [
                'item_type' => 'package',
                'description' => $this->string('package_name')->trim()->value(),
                'quantity' => 1,
                'unit_price' => $price,
                'amount' => $price,
                'sort_order' => $order++,
            ];
        }

        // Taxi item
        if ($this->boolean('has_taxi')) {
            $vehicle = $this->filled('vehicle_id')
                ? Vehicle::with(['type', 'capacity'])->find((int) $this->input('vehicle_id'))
                : null;

            $pricePerKm = (float) $this->input('price_per_km', 0);
            $distanceKm = (float) $this->input('total_distance_km', 0);
            $taxiAmount = round($pricePerKm * $distanceKm, 2);

            $vehicleName = $vehicle ? $vehicle->name : null;
            $vehicleType = $vehicle?->type?->name;
            $vehicleCapacity = $vehicle?->capacity?->label;

            $desc = 'Taxi service' . ($vehicleName ? " ({$vehicleName})" : '') . " - {$distanceKm} km @ ₹{$pricePerKm}/km";

            $items[] = [
                'item_type' => 'taxi',
                'description' => $desc,
                'quantity' => $distanceKm > 0 ? $distanceKm : 1,
                'unit_price' => $distanceKm > 0 ? $pricePerKm : $taxiAmount,
                'amount' => $taxiAmount,
                'vehicle_id' => $vehicle?->id,
                'vehicle_name' => $vehicleName,
                'vehicle_type' => $vehicleType,
                'vehicle_capacity' => $vehicleCapacity,
                'price_per_km' => $pricePerKm,
                'total_distance_km' => $distanceKm,
                'sort_order' => $order++,
            ];
        }

        // Additional items
        $names = (array) $this->input('item_descriptions', []);
        $quantities = (array) $this->input('item_quantities', []);
        $prices = (array) $this->input('item_prices', []);

        for ($i = 0; $i < count($names); $i++) {
            $desc = isset($names[$i]) ? trim((string) $names[$i]) : '';
            if ($desc !== '') {
                $qty = isset($quantities[$i]) && (float) $quantities[$i] > 0 ? (float) $quantities[$i] : 1;
                $unit = isset($prices[$i]) ? (float) $prices[$i] : 0;
                $amount = round($qty * $unit, 2);

                $items[] = [
                    'item_type' => 'other',
                    'description' => $desc,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'amount' => $amount,
                    'sort_order' => $order++,
                ];
            }
        }

        return $items;
    }

    public function computedInvoiceData(?Invoice $existing = null): array
    {
        $items = $this->computedItemsData();
        $subtotal = array_sum(array_column($items, 'amount'));
        $taxRate = (float) $this->input('tax_rate', 0);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        $settings = Setting::first();
        $businessDetails = $existing?->business_details ?? [
            'name' => $settings->business_name ?? 'Kutch Tours & Taxi',
            'contact' => $settings->contact_number ?? '',
            'contact_2' => $settings->contact_number_2 ?? '',
            'email' => $settings->contact_email ?? '',
            'address' => $settings->address ?? '',
            'gst_number' => $settings->gst_number ?? '',
        ];

        return [
            'invoice_number' => $existing ? $existing->invoice_number : Invoice::generateInvoiceNumber($this->input('invoice_date')),
            'invoice_date' => $this->input('invoice_date'),
            'customer_name' => $this->string('customer_name')->trim()->value(),
            'customer_contact' => $this->input('customer_contact') ? $this->string('customer_contact')->trim()->value() : null,
            'customer_email' => $this->input('customer_email') ? $this->string('customer_email')->trim()->value() : null,
            'customer_address' => $this->input('customer_address') ? $this->string('customer_address')->trim()->value() : null,
            'customer_gst_number' => $this->input('customer_gst_number') ? $this->string('customer_gst_number')->trim()->value() : null,
            'business_details' => $businessDetails,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'notes' => $this->input('notes') ? $this->string('notes')->trim()->value() : null,
        ];
    }
}
