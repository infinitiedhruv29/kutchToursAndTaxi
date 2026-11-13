<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'taxi_route_id',
    'vehicle_id',
    'one_way_fare',
    'round_trip_fare',
    'is_default',
    'sort_order',
])]
class TaxiRouteFare extends Model
{
    protected function casts(): array
    {
        return [
            'taxi_route_id' => 'integer',
            'vehicle_id' => 'integer',
            'one_way_fare' => 'decimal:2',
            'round_trip_fare' => 'decimal:2',
            'is_default' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TaxiRoute::class, 'taxi_route_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}

