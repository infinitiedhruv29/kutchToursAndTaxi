<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'package_id',
    'day_number',
    'title',
    'description',
    'spots',
    'note',
])]
class PackageItinerary extends Model
{
    protected function casts(): array
    {
        return [
            'spots' => 'array',
            'day_number' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
