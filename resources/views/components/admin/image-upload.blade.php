@props(['name' => 'image', 'path' => null, 'label' => 'Image', 'previewAlt' => 'Image preview'])
<div data-image-upload class="space-y-4">
    <div class="flex h-48 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
        <img data-image-preview @if ($path) src="{{ Storage::disk('public')->url($path) }}" @endif alt="{{ $previewAlt }}" @class(['max-h-full max-w-full object-contain', 'hidden' => !$path])>
        <span data-image-placeholder @class(['text-sm text-slate-500 dark:text-slate-400', 'hidden' => (bool) $path])>No image selected</span>
    </div>
    <x-admin.field :name="$name" :label="$label" type="file" accept="image/jpeg,image/png,image/webp" hint="Optional JPEG, PNG, or WebP up to 2 MB." data-image-file />
    @if ($path)<x-admin.checkbox :name="'remove_'.$name" label="Remove saved image" data-image-remove hint="A replacement upload takes precedence over removal." />@endif
    <p data-image-preview-message role="status" class="text-sm text-slate-500 dark:text-slate-400"></p>
</div>
