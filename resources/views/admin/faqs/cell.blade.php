@php($editUrl = route('admin.faqs.edit', [$faq, 'return_to' => route('admin.faqs.index', request()->only(['q', 'status', 'sort', 'direction', 'page', 'per_page']))]))
@switch($column)
    @case('question')
        <a href="{{ $editUrl }}" data-return-link class="block min-w-48 max-w-lg break-words rounded-sm font-semibold text-slate-900 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-100 dark:hover:text-brand-200">{{ $faq->question }}</a>
        @break
    @case('answer')
        <p class="min-w-48 max-w-lg break-words">{{ \Illuminate\Support\Str::limit(\App\Models\Faq::answerText($faq->answer), 180) }}</p>
        @break
    @case('visibility')
        <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-medium', 'bg-brand-50 text-brand-800 dark:bg-brand-500/10 dark:text-brand-200' => $faq->is_published, 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' => !$faq->is_published])>{{ $faq->is_published ? 'Published' : 'Draft' }}</span>
        @break
    @case('sort_order') {{ $faq->sort_order }} @break
    @case('actions')
        <x-admin.record-actions :edit-url="$editUrl" :delete-url="route('admin.faqs.destroy', $faq)" :name="$faq->question" delete-title="Delete FAQ?" />
        @break
@endswitch
