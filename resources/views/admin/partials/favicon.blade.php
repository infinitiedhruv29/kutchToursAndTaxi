@if ($businessSettings->favicon_path)
    <link rel="icon" type="image/png" href="{{ Storage::disk('public')->url($businessSettings->favicon_path) }}">
@endif
