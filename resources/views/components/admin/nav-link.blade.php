@props(['href', 'active' => false, 'icon' => 'grid'])
<a href="{{ $href }}" @if ($active) aria-current="page" @endif
    {{ $attributes->class([
        'flex min-h-11 items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500',
        'bg-brand-500/15 text-brand-200' => $active,
        'text-slate-400 hover:bg-slate-800 hover:text-white' => ! $active,
    ]) }}>
    <x-admin.icon :name="$icon" />{{ $slot }}
</a>
