@props(['type' => 'success'])
<div role="{{ $type === 'error' ? 'alert' : 'status' }}" {{ $attributes->class([
    'rounded-xl border px-4 py-3 text-sm',
    'border-brand-200 bg-brand-50 text-brand-800' => $type === 'success',
    'border-red-200 bg-red-50 text-red-800' => $type === 'error',
]) }}>{{ $slot }}</div>
