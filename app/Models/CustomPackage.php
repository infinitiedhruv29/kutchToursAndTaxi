<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'client_name',
    'client_phone',
    'title',
    'duration',
    'highlight',
    'price_2p',
    'price_4p',
    'price_6p',
    'inclusions',
    'exclusions',
    'itineraries',
])]
class CustomPackage extends Model
{
    protected function casts(): array
    {
        return [
            'price_2p' => 'decimal:2',
            'price_4p' => 'decimal:2',
            'price_6p' => 'decimal:2',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'itineraries' => 'array',
        ];
    }
}
