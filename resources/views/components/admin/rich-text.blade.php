@props(['name', 'label', 'value' => null])
@php($input = old($name, $value))
<div data-rich-editor data-field="{{ $name }}">
    <label id="{{ $name }}-label" for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    <div data-editor-enhanced class="hidden overflow-hidden rounded-sm border {{ $errors->has($name) ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }}">
        <div role="toolbar" aria-label="{{ $label }} formatting" class="flex flex-wrap gap-1 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 p-2">
            @foreach (['paragraph' => 'Paragraph', 'h2' => 'H2', 'h3' => 'H3', 'bold' => 'Bold', 'italic' => 'Italic', 'bulletList' => 'Bullets', 'orderedList' => 'Numbered list', 'link' => 'Link', 'undo' => 'Undo', 'redo' => 'Redo'] as $command => $text)
                <button type="button" data-editor-command="{{ $command }}" aria-label="{{ $text }}" @if (! in_array($command, ['undo', 'redo'])) aria-pressed="false" @endif class="rounded-lg px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 focus-visible:outline-2 focus-visible:outline-brand-600 aria-pressed:bg-brand-100 aria-pressed:text-brand-800 disabled:opacity-40">{{ $text }}</button>
            @endforeach
        </div>
        <div data-editor-link-panel class="hidden border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 p-3">
            <label for="{{ $name }}-link" class="mb-2 block text-xs font-medium text-slate-600 dark:text-slate-300">Link address (HTTP or HTTPS)</label>
            <div class="flex flex-wrap gap-2">
                <input id="{{ $name }}-link" type="url" data-editor-link-input class="min-h-10 min-w-0 flex-1 rounded-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm focus:outline-2 focus:outline-brand-600">
                <x-admin.button type="button" data-editor-link-apply>Apply link</x-admin.button>
                <x-admin.button type="button" variant="secondary" data-editor-link-remove>Remove link</x-admin.button>
                <x-admin.button type="button" variant="secondary" data-editor-link-cancel>Cancel</x-admin.button>
            </div>
        </div>
        <div data-editor-canvas></div>
    </div>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="10" data-editor-source maxlength="100000" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $name }}-help" @if ($errors->has($name)) aria-invalid="true" @endif class="block w-full rounded-sm border border-slate-200 dark:border-slate-700 p-4 text-sm focus:outline-2 focus:outline-brand-600">{{ is_string($input) ? $input : '' }}</textarea>
    <p id="{{ $name }}-help" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Use headings, paragraphs, lists, and links. Images, embedded content, and custom colors are not supported.</p>
    <p data-editor-fallback class="mt-2 hidden text-sm text-amber-800 dark:text-amber-200">The rich-text editor could not load. Your content is still available in the text area; existing formatting appears as HTML.</p>
    @error($name)<p data-field-error class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
</div>
