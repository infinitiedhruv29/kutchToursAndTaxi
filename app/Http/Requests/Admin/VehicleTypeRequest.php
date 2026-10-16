<?php

namespace App\Http\Requests\Admin;

use App\Models\VehicleType;

class VehicleTypeRequest extends VehicleLookupRequest
{
    protected function lookupClass(): string { return VehicleType::class; }

    protected function labelField(): string { return 'name'; }

    protected function parameter(): string { return 'vehicleType'; }
}
