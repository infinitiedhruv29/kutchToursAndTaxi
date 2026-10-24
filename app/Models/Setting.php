<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'business_name', 'logo_path', 'dark_logo_path', 'favicon_path', 'primary_color',
    'contact_number', 'contact_number_2', 'whatsapp_number', 'contact_email',
    'address', 'google_map_link', 'instagram_link', 'facebook_link', 'youtube_link',
    'gst_number', 'general_inclusions', 'general_exclusions',
])]
class Setting extends Model
{
    public const int SINGLETON_ID = 1;

    public const array CONTENT_FIELDS = [
        'business_name', 'primary_color', 'contact_number', 'contact_number_2', 'whatsapp_number',
        'contact_email', 'address', 'google_map_link', 'instagram_link',
        'facebook_link', 'youtube_link', 'gst_number', 'general_inclusions', 'general_exclusions',
    ];

    public const array BRANDING_FILES = [
        'logo' => 'logo_path',
        'dark_logo' => 'dark_logo_path',
        'favicon' => 'favicon_path',
    ];

    public static function current(): self
    {
        return static::find(self::SINGLETON_ID)
            ?? (new static)->forceFill(['id' => self::SINGLETON_ID]);
    }

    protected function casts(): array
    {
        return [
            'general_inclusions' => 'array',
            'general_exclusions' => 'array',
        ];
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->logo_path, 'images/logo/logo-black.svg');
    }

    public function getDarkLogoUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->dark_logo_path ?? $this->logo_path, 'images/logo/logo-gold.svg');
    }

    public function getFaviconUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->favicon_path, 'images/favicon.svg');
    }

    public function resolveMediaUrl(?string $path, string $fallback): string
    {
        if (! $path) {
            return asset($fallback);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        if (file_exists(public_path('storage/'.$path))) {
            return asset('storage/'.$path);
        }

        return asset($fallback);
    }
}
