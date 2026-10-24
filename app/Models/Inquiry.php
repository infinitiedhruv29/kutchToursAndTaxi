<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'phone',
    'email',
    'service_type',
    'taxi_route_id',
    'package_id',
    'service_title',
    'origin_name',
    'destination_name',
    'direction',
    'trip_type',
    'travel_date',
    'passenger_count',
    'message',
    'status',
    'admin_notes',
    'contacted_at',
])]
class Inquiry extends Model
{
    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'contacted_at' => 'datetime',
            'passenger_count' => 'integer',
        ];
    }

    public function taxiRoute()
    {
        return $this->belongsTo(TaxiRoute::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
