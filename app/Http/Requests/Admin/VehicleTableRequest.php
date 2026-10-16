<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class VehicleTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string { return 'sort_order'; }

    protected function sortableColumns(): array { return [0 => 'name', 3 => 'price_per_km', 5 => 'sort_order']; }

    public function rules(): array
    {
        return parent::rules() + [
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'vehicle_type_id' => ['nullable', 'integer', Rule::exists('vehicle_types', 'id')],
            'vehicle_capacity_id' => ['nullable', 'integer', Rule::exists('vehicle_capacities', 'id')],
        ];
    }
}
