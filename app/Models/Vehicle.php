<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'vehicle_type_id', 'vehicle_capacity_id', 'image_path', 'image_alt', 'price_per_km', 'min_km_per_day', 'notes', 'is_published', 'sort_order'])]
class Vehicle extends Model
{
    protected function casts(): array
    {
        return ['vehicle_type_id' => 'integer', 'vehicle_capacity_id' => 'integer', 'price_per_km' => 'decimal:2', 'min_km_per_day' => 'integer', 'is_published' => 'boolean', 'sort_order' => 'integer'];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function capacity(): BelongsTo
    {
        return $this->belongsTo(VehicleCapacity::class, 'vehicle_capacity_id');
    }

    public static function suggestedSlug(string $name): string
    {
        return Str::limit(Str::slug($name) ?: 'vehicle', 240, '');
    }

    public static function uniqueSlug(string $base): string
    {
        $slug = $base;
        $suffix = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
