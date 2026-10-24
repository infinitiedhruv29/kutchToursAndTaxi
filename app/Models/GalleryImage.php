<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['gallery_category_id', 'image_path', 'caption', 'image_alt', 'is_published', 'sort_order'])]
class GalleryImage extends Model
{
    protected function casts(): array
    {
        return [
            'gallery_category_id' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (! $this->image_path) {
                    return '';
                }
                if (file_exists(public_path($this->image_path))) {
                    return asset($this->image_path);
                }
                return Storage::disk('public')->url($this->image_path);
            }
        );
    }
}
