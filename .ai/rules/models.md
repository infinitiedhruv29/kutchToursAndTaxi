# Models & Eloquent Rules

**Applies to**: `app/Models/**`

- **Mass Assignment**: Declare fillable fields using the class-level `#[Fillable([...])]` attribute (`Illuminate\Database\Eloquent\Attributes\Fillable`), not the `protected $fillable = []` property.
- **Attribute Casting**: Define model attribute casting via `protected function casts(): array`, not the `protected $casts = []` property.
- **Slugs**: Any model with a public slug must implement `public static function suggestedSlug(...)` and `public static function uniqueSlug(string $base): string` to prevent collisions.
- **Mutators and Accessors**: Use the modern `Attribute::make(get: ..., set: ...)` style from `Illuminate\Database\Eloquent\Casts\Attribute`. Sanitize rich text inputs using `Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface`.
