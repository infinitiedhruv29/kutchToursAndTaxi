<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

#[Fillable(['question', 'answer', 'is_published', 'sort_order'])]
class Faq extends Model
{
    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'sort_order' => 'integer'];
    }

    public static function sanitizeAnswer(string $value): string
    {
        return app(HtmlSanitizerInterface::class)->sanitize($value);
    }

    public static function answerText(string $value): string
    {
        $spaced = preg_replace('/<\/?(?:p|h[23]|li|ul|ol|br)\b[^>]*>/i', ' ', $value);

        return trim(html_entity_decode(strip_tags($spaced), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    protected function answer(): Attribute
    {
        return Attribute::make(set: fn (string $value): string => static::sanitizeAnswer($value));
    }
}
