<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

#[Fillable([
    'title', 'slug', 'origin_name', 'destination_name', 'short_description', 'description',
    'image_path', 'image_alt', 'distance_km', 'estimated_duration_minutes',
    'forward_one_way_fare', 'forward_round_trip_fare', 'reverse_enabled',
    'reverse_one_way_fare', 'reverse_round_trip_fare', 'is_published', 'is_featured',
    'sort_order', 'seo_title', 'seo_description',
])]
class TaxiRoute extends Model
{
    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
            'forward_one_way_fare' => 'decimal:2',
            'forward_round_trip_fare' => 'decimal:2',
            'reverse_one_way_fare' => 'decimal:2',
            'reverse_round_trip_fare' => 'decimal:2',
            'reverse_enabled' => 'boolean',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'estimated_duration_minutes' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected function description(): Attribute
    {
        return Attribute::make(set: function (?string $value): ?string {
            if ($value === null) {
                return null;
            }

            $html = app(HtmlSanitizerInterface::class)->sanitize($value);
            $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return preg_match('/[^\s\x{00A0}]/u', $text) ? $html : null;
        });
    }

    public function effectiveReverseOneWayFare(): ?string
    {
        return $this->reverse_enabled ? ($this->reverse_one_way_fare ?? $this->forward_one_way_fare) : null;
    }

    public function effectiveReverseRoundTripFare(): ?string
    {
        return $this->reverse_enabled ? ($this->reverse_round_trip_fare ?? $this->forward_round_trip_fare) : null;
    }

    public static function suggestedSlug(string $origin, string $destination): string
    {
        return Str::limit(Str::slug($origin.' to '.$destination) ?: 'taxi-route', 240, '');
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
