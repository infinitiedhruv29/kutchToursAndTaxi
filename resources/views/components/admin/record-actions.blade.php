@props(['editUrl', 'deleteUrl', 'name', 'deleteTitle' => 'Delete record?', 'warning' => 'This cannot be undone.'])
<div class="flex items-center gap-3">
    <a href="{{ $editUrl }}" data-return-link class="rounded-lg px-2 py-2 text-sm font-medium text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-brand-200">Edit<span class="sr-only"> {{ $name }}</span></a>
    <button type="button" data-confirm-delete data-action="{{ $deleteUrl }}" data-title="{{ $name }}" data-delete-title="{{ $deleteTitle }}" data-delete-warning="{{ $warning }}" class="rounded-lg px-2 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus-visible:outline-2 focus-visible:outline-red-600 dark:text-red-300 dark:hover:bg-red-950/40">Delete<span class="sr-only"> {{ $name }}</span></button>
</div>
