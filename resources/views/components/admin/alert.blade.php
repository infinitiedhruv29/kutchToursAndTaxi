@props(['type' => 'success'])
<div role="{{ $type === 'error' ? 'alert' : 'status' }}" {{ $attributes->class([
    'rounded-xl border px-4 py-3 text-sm',
    'border-brand-200 bg-brand-50 dark:bg-brand-500/10 text-brand-800 dark:text-brand-200' => $type === 'success',
    'border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-950/40 text-red-800' => $type === 'error',
]) }}>{{ $slot }}</div>
