@props(['name', 'label', 'value' => null, 'hint' => null, 'rows' => 4])
@php
    $error = $errors->first($name) ?: $errors->first($name.'.*');
    $input = old($name, $value);
    $displayValue = is_array($input)
        ? implode("\n", array_filter($input, 'is_string'))
        : (is_string($input) ? $input : '');
@endphp
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
        @if ($error) aria-invalid="true" @endif
        @if ($hint || $error) aria-describedby="{{ $name }}-help" @endif
        {{ $attributes->class([
            'block w-full rounded-sm border bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-600 focus:outline-2 focus:outline-brand-100',
            'border-red-400' => (bool) $error,
            'border-slate-200' => ! $error,
        ]) }}>{{ $displayValue }}</textarea>
    @if ($hint || $error)
        <p id="{{ $name }}-help" class="mt-2 text-sm {{ $error ? 'text-red-700' : 'text-slate-500' }}">{{ $error ?: $hint }}</p>
    @endif
</div>
