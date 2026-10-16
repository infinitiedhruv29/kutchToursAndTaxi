@props(['name', 'label', 'type' => 'text', 'value' => null, 'bag' => 'default', 'hint' => null, 'id' => null])
@php
    $fieldId = $id ?? $name;
    $error = $errors->getBag($bag)->first($name);
@endphp
<div data-field="{{ $name }}">
    <label for="{{ $fieldId }}" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    <input id="{{ $fieldId }}" name="{{ $name }}" type="{{ $type }}"
        @if (!in_array($type, ['password', 'file'])) value="{{ old($name, $value) }}" @endif
        @if ($error) aria-invalid="true" @endif
        @if ($hint || $error) aria-describedby="{{ $hint ? $fieldId.'-help' : '' }} {{ $error ? $fieldId.'-error' : '' }}" @endif
        {{ $attributes->class([
            'block min-h-11 w-full rounded-sm border bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:border-brand-600 focus:outline-2 focus:outline-brand-100 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 dark:file:bg-slate-800 file:px-3 file:py-1 file:text-sm file:font-medium',
            'border-red-400' => (bool) $error,
            'border-slate-200 dark:border-slate-700' => !$error,
        ]) }}>
    @if ($hint)
        <p id="{{ $fieldId }}-help" class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
    @if ($error)
        <p id="{{ $fieldId }}-error" data-field-error class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
    @endif
</div>
