@php($editUrl = route('admin.destinations.edit', [$destination, 'return_to' => route('admin.destinations.index', request()->only(['q', 'status', 'featured', 'sort', 'direction', 'page', 'per_page']))]))
@switch($column)
    @case('destination')
        <div class="flex min-w-48 items-center gap-3">
            @if ($destination->image_path)<img src="{{ Storage::disk('public')->url($destination->image_path) }}" alt="{{ $destination->image_alt ?? '' }}" class="size-12 shrink-0 rounded-lg object-cover" loading="lazy">
            @else<span class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800"><x-admin.icon name="route" /></span>@endif
            <a href="{{ $editUrl }}" data-return-link class="break-words rounded-sm font-semibold text-slate-900 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-100 dark:hover:text-brand-200">{{ $destination->name }}</a>
        </div>
        @break
    @case('visibility')
        <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-medium', 'bg-brand-50 text-brand-800 dark:bg-brand-500/10 dark:text-brand-200' => $destination->is_published, 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' => !$destination->is_published])>{{ $destination->is_published ? 'Published' : 'Draft' }}</span>
        @if ($destination->is_featured)<span class="mt-1 block text-xs text-amber-700 dark:text-amber-300">Featured</span>@endif
        @break
    @case('sort_order') {{ $destination->sort_order }} @break
    @case('actions')
        <x-admin.record-actions :edit-url="$editUrl" :delete-url="route('admin.destinations.destroy', $destination)" :name="$destination->name" delete-title="Delete destination?" />
        @break
@endswitch
