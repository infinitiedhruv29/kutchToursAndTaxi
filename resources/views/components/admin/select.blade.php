@props(['name', 'label', 'value' => null])
<div data-field="{{ $name }}">
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->class(['min-h-11 w-full rounded-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:border-brand-600 focus:outline-2 focus:outline-brand-100']) }}>
        {{ $slot }}
    </select>
    @error($name)<p data-field-error class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
</div>
