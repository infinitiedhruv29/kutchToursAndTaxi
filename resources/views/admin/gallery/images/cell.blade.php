@php($editUrl = route('admin.gallery-images.edit', [$image, 'return_to' => route('admin.gallery-images.index', request()->only(['q', 'status', 'category_id', 'sort', 'direction', 'page', 'per_page']))]))
@switch($column)
    @case('image')
        <div class="flex items-center gap-3">
            @if ($image->image_path)
                <img src="{{ Storage::disk('public')->url($image->image_path) }}" alt="{{ $image->image_alt ?? $image->caption ?? 'Gallery photo' }}" class="size-16 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700" loading="lazy">
            @else
                <span class="flex size-16 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800">
                    <x-admin.icon name="route" />
                </span>
            @endif
        </div>
        @break
    @case('caption')
        <div class="min-w-48 max-w-sm">
            <a href="{{ $editUrl }}" data-return-link class="font-semibold text-slate-900 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-100 dark:hover:text-brand-200">
                {{ $image->caption ?: 'Untitled Photo' }}
            </a>
            @if ($image->image_alt)
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Alt: {{ $image->image_alt }}</p>
            @endif
        </div>
        @break
    @case('category')
        <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
            {{ $image->category?->name ?? 'Uncategorized' }}
        </span>
        @break
    @case('visibility')
        <span @class([
            'inline-flex rounded-full px-2.5 py-1 text-xs font-medium',
            'bg-brand-50 text-brand-800 dark:bg-brand-500/10 dark:text-brand-200' => $image->is_published,
            'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' => !$image->is_published,
        ])>
            {{ $image->is_published ? 'Published' : 'Draft' }}
        </span>
        @break
    @case('sort_order')
        {{ $image->sort_order }}
        @break
    @case('actions')
        <x-admin.record-actions
            :edit-url="$editUrl"
            :delete-url="route('admin.gallery-images.destroy', $image)"
            :name="$image->caption ?: 'this photo'"
            delete-title="Delete photo?"
        />
        @break
@endswitch
