<?php

namespace App\Http\Requests\Admin;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class VehicleRequest extends FormRequest
{
    protected bool $generatedSlug = false;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function generatedSlug(): bool
    {
        return $this->generatedSlug;
    }

    public function slugBase(): string
    {
        return Vehicle::suggestedSlug($this->validated('name'));
    }

    protected function prepareForValidation(): void
    {
        if (! ($this->route('vehicle') instanceof Vehicle) && is_string($this->input('name'))
            && ($this->boolean('auto_slug') || ! $this->filled('slug'))) {
            $this->generatedSlug = true;
            $this->merge(['slug' => Vehicle::uniqueSlug(Vehicle::suggestedSlug($this->input('name')))]);
        }
    }

    public function rules(): array
    {
        $vehicle = $this->route('vehicle');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('vehicles')->ignore($vehicle instanceof Vehicle ? $vehicle->id : null)],
            'auto_slug' => ['sometimes', 'boolean'],
            'vehicle_type_id' => ['required', 'integer', Rule::exists('vehicle_types', 'id')],
            'vehicle_capacity_id' => ['required', 'integer', Rule::exists('vehicle_capacities', 'id')],
            'price_per_km' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
            'min_km_per_day' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'remove_image' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    public function vehicleData(): array
    {
        $fields = ['name', 'slug', 'vehicle_type_id', 'vehicle_capacity_id', 'price_per_km', 'min_km_per_day', 'notes', 'image_alt', 'sort_order'];

        return array_replace(array_fill_keys($fields, null), $this->safe()->only($fields), ['is_published' => $this->boolean('is_published')]);
    }
}
