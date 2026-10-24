@props(['settings', 'dark' => false])
@php
    $businessName = $settings->business_name ?? 'Tours & Taxi';
    $darkLogo = $settings->dark_logo_path;
    $logo = $dark ? ($darkLogo ?? $settings->logo_path) : $settings->logo_path;
@endphp
<div {{ $attributes->class(['flex min-w-0 flex-wrap items-center gap-3']) }}>
    @if ($logo)
        <img src="{{ Storage::disk('public')->url($logo) }}" alt="{{ $businessName }} logo" @class(['h-12 w-auto max-w-40 shrink-0 object-contain', 'dark:hidden' => !$dark && $darkLogo])>
    @else
        <span @class(['flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white', 'dark:hidden' => !$dark && $darkLogo])><x-admin.icon name="route" /></span>
    @endif
    @if (!$dark && $darkLogo)
        <img src="{{ Storage::disk('public')->url($darkLogo) }}" alt="{{ $businessName }} logo" class="hidden h-12 w-auto max-w-40 shrink-0 object-contain dark:block">
    @endif
    <div class="min-w-0">
        <p class="break-words text-sm font-semibold {{ $dark ? 'text-white' : 'text-slate-900 dark:text-slate-100' }}">{{ $businessName }}</p>
        <p class="mt-0.5 text-xs text-slate-400">Administration</p>
    </div>
</div>
