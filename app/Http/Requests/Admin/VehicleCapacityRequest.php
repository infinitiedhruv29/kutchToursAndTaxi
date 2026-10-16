<?php

namespace App\Http\Requests\Admin;

use App\Models\VehicleCapacity;

class VehicleCapacityRequest extends VehicleLookupRequest
{
    protected function lookupClass(): string { return VehicleCapacity::class; }

    protected function labelField(): string { return 'label'; }

    protected function parameter(): string { return 'vehicleCapacity'; }

    public function rules(): array
    {
        return parent::rules() + ['passenger_capacity' => ['required', 'integer', 'between:1,65535']];
    }
}
