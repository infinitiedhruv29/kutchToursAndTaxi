@props(['name', 'label', 'value' => null, 'hint' => null, 'rows' => 4])
@php
    $error = $errors->first($name) ?: $errors->first($name.'.*');
    $input = old($name, $value);
    $displayValue = is_array($input)
        ? implode("\n", array_filter($input, 'is_string'))
        : (is_string($input) ? $input : '');
@endphp
<div data-field="{{ $name }}">
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
        @if ($error) aria-invalid="true" @endif
        @if ($hint || $error) aria-describedby="{{ $hint ? $name.'-help' : '' }} {{ $error ? $name.'-error' : '' }}" @endif
        {{ $attributes->class([
            'block w-full rounded-sm border bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:border-brand-600 focus:outline-2 focus:outline-brand-100',
            'border-red-400' => (bool) $error,
            'border-slate-200 dark:border-slate-700' => ! $error,
        ]) }}>{{ $displayValue }}</textarea>
    @if ($hint)
        <p id="{{ $name }}-help" class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
    @if ($error)
        <p id="{{ $name }}-error" data-field-error class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
    @endif
</div>
