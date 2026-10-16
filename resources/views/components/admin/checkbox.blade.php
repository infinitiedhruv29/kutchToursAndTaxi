@props(['name', 'label', 'checked' => false, 'hint' => null])
<div data-field="{{ $name }}">
    <label class="flex min-h-11 items-center gap-2.5 text-sm font-medium text-slate-700 dark:text-slate-200">
        <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) {{ $attributes->class(['size-4 shrink-0 accent-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600']) }}>
        {{ $label }}
    </label>
    @if ($hint)<p class="text-xs leading-5 text-slate-500 dark:text-slate-400">{{ $hint }}</p>@endif
    @error($name)<p data-field-error class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
</div>
