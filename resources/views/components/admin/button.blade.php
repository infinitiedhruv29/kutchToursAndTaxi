@props(['type' => 'submit', 'variant' => 'primary'])
<button type="{{ $type }}" {{ $attributes->class([
    'inline-flex min-h-11 items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 disabled:cursor-not-allowed disabled:opacity-50',
    'bg-brand-700 text-white hover:bg-brand-800' => $variant === 'primary',
    'border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-950' => $variant === 'secondary',
]) }}>{{ $slot }}</button>
