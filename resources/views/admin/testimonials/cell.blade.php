@switch($column)
    @case('client')
        <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $testimonial->client_name }}</div>
        @break

    @case('service')
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
            {{ $testimonial->service_name ?? 'General Tour' }}
        </span>
        @break

    @case('review')
        <div class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 italic max-w-md">
            "{{ $testimonial->review }}"
        </div>
        @break

    @case('status')
        @if ($testimonial->is_published)
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                Published
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                Draft
            </span>
        @endif
        @break

    @case('sort_order')
        <span class="text-xs font-medium text-slate-500">{{ $testimonial->sort_order }}</span>
        @break

    @case('actions')
        @php($editUrl = route('admin.testimonials.edit', ['testimonial' => $testimonial, 'return_to' => request()->fullUrl()]))
        <x-admin.record-actions :edit-url="$editUrl" :delete-url="route('admin.testimonials.destroy', $testimonial)" :name="$testimonial->client_name" delete-title="Delete testimonial?" />
        @break
@endswitch
