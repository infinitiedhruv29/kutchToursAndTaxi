<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['label', 'passenger_capacity', 'sort_order'])]
class VehicleCapacity extends Model
{
    protected function casts(): array
    {
        return ['passenger_capacity' => 'integer', 'sort_order' => 'integer'];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
