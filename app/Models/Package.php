<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'package_type',
    'highlight',
    'duration',
    'description',
    'image_path',
    'image_alt',
    'price_2p',
    'price_4p',
    'price_6p',
    'is_published',
    'is_featured',
    'sort_order',
    'seo_title',
    'seo_description',
])]
class Package extends Model
{
    protected function casts(): array
    {
        return [
            'price_2p' => 'decimal:2',
            'price_4p' => 'decimal:2',
            'price_6p' => 'decimal:2',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function suggestedSlug(string $title): string
    {
        return Str::slug($title);
    }

    public static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $counter = 1;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
