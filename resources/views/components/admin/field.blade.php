@props(['name', 'label', 'type' => 'text', 'value' => null, 'bag' => 'default', 'hint' => null, 'id' => null])
@php
    $fieldId = $id ?? $name;
    $error = $errors->getBag($bag)->first($name);
@endphp
<div>
    <label for="{{ $fieldId }}" class="mb-2 block text-sm font-medium text-slate-700">{{ $label }}</label>
    <input id="{{ $fieldId }}" name="{{ $name }}" type="{{ $type }}"
        @if (!in_array($type, ['password', 'file'])) value="{{ old($name, $value) }}" @endif
        @if ($error) aria-invalid="true" @endif
        @if ($hint || $error) aria-describedby="{{ $fieldId }}-help" @endif
        {{ $attributes->class([
            'block min-h-11 w-full rounded-sm border bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-600 focus:outline-2 focus:outline-brand-100 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1 file:text-sm file:font-medium',
            'border-red-400' => (bool) $error,
            'border-slate-200' => !$error,
        ]) }}>
    @if ($hint || $error)
        <p id="{{ $fieldId }}-help" class="mt-2 text-sm {{ $error ? 'text-red-700' : 'text-slate-500' }}">
            {{ $error ?: $hint }}</p>
    @endif
</div>
