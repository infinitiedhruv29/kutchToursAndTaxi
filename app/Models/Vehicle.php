<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'vehicle_type_id', 'vehicle_capacity_id', 'image_path', 'image_alt', 'price_per_km', 'price_for_12_hrs', 'min_km_per_day', 'notes', 'is_published', 'sort_order'])]
class Vehicle extends Model
{
    protected function casts(): array
    {
        return ['vehicle_type_id' => 'integer', 'vehicle_capacity_id' => 'integer', 'price_per_km' => 'decimal:2', 'price_for_12_hrs' => 'decimal:2', 'min_km_per_day' => 'integer', 'is_published' => 'boolean', 'sort_order' => 'integer'];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function capacity(): BelongsTo
    {
        return $this->belongsTo(VehicleCapacity::class, 'vehicle_capacity_id');
    }

    public function routeFares(): HasMany
    {
        return $this->hasMany(TaxiRouteFare::class, 'vehicle_id');
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $name = $this->name;
                $capLabel = $this->capacity->label ?? null;

                if ($capLabel && ! str_contains(strtolower($name), strtolower($capLabel)) && ! preg_match('/\b\d+\s*seater\b/i', $name)) {
                    return "{$name} ({$capLabel})";
                }

                return $name;
            }
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (! $this->image_path) {
                    return asset('images/taxi-page/dzire.webp');
                }
                if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
                    return $this->image_path;
                }
                if (file_exists(public_path($this->image_path))) {
                    return asset($this->image_path);
                }
                return Storage::disk('public')->url($this->image_path);
            }
        );
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
