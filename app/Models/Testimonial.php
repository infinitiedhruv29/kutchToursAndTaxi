<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'client_name',
    'service_name',
    'youtube_link',
    'youtube_video_id',
    'review',
    'is_published',
    'sort_order',
])]
class Testimonial extends Model
{
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function parseYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/|shorts\/)|(?:(?:watch)?\?vi?=|&vi?=))([^#\&\?]*).*/';
        if (preg_match($pattern, $url, $matches)) {
            if (isset($matches[1]) && strlen($matches[1]) === 11) {
                return $matches[1];
            }
        }

        return null;
    }
}
