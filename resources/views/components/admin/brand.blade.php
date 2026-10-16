@props(['settings', 'dark' => false])
@php
    $businessName = $settings->business_name ?? 'Tours & Taxi';
    $logo = $dark ? $settings->dark_logo_path : $settings->logo_path;
@endphp
<div {{ $attributes->class(['flex min-w-0 flex-wrap items-center gap-3']) }}>
    @if ($logo)
        <img src="{{ Storage::disk('public')->url($logo) }}" alt="{{ $businessName }} logo" class="max-h-11 max-w-36 shrink-0 object-contain">
    @else
        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white"><x-admin.icon name="route" /></span>
    @endif
    <div class="min-w-0">
        <p class="break-words text-sm font-semibold {{ $dark ? 'text-white' : 'text-slate-900' }}">{{ $businessName }}</p>
        <p class="mt-0.5 text-xs text-slate-400">Administration</p>
    </div>
</div>
