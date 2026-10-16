@props(['name', 'label', 'path' => null, 'hint', 'dark' => false, 'accept' => 'image/jpeg,image/png,image/webp'])
<div class="min-w-0 space-y-4">
    <div @class([
        'flex h-32 items-center justify-center overflow-hidden rounded-xl border p-5',
        'border-slate-700 bg-slate-900' => $dark,
        'border-slate-200 bg-slate-50' => ! $dark,
    ])>
        @if ($path)
            <img src="{{ Storage::disk('public')->url($path) }}" alt="Current {{ strtolower($label) }}" class="max-h-full max-w-full object-contain">
        @else
            <p class="text-center text-xs {{ $dark ? 'text-slate-400' : 'text-slate-500' }}">No {{ strtolower($label) }} uploaded</p>
        @endif
    </div>
    <x-admin.field :name="$name" :label="$label" type="file" :accept="$accept" :hint="$hint" />
    @if ($path)
        <label class="flex min-h-11 items-center gap-2.5 text-sm text-slate-600">
            <input type="checkbox" name="remove_{{ $name }}" value="1" @checked(old('remove_'.$name))
                class="size-4 accent-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
            Remove current {{ strtolower($label) }}
        </label>
    @endif
</div>
