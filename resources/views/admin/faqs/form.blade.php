<x-admin.layout :title="$faq->exists ? 'Edit FAQ' : 'Create FAQ'" subtitle="Manage a general question and its formatted answer.">
    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" data-admin-form data-loading-text="Saving FAQ…" class="space-y-6">
        @csrf @if ($faq->exists) @method('PUT') @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        @if ($errors->any())<x-admin.alert type="error" data-validation-summary>Please check the highlighted fields.</x-admin.alert>@endif
        <x-admin.card title="Question and answer">
            <div class="space-y-5 p-5 sm:p-6">
                <x-admin.textarea name="question" label="Question" :value="$faq->question" rows="3" maxlength="1000" required hint="Plain text, up to 1,000 characters." />
                <x-admin.rich-text name="answer" label="Answer" :value="$faq->answer" />
            </div>
        </x-admin.card>
        <x-admin.card title="Visibility and order">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6"><x-admin.checkbox name="is_published" label="Published" :checked="$faq->is_published" hint="Leave unchecked to save as a draft." /><x-admin.field name="sort_order" label="Display order" type="number" :value="$faq->sort_order ?? 0" min="0" max="4294967295" step="1" required hint="Lower numbers appear first." /></div>
        </x-admin.card>
        <div class="flex flex-wrap items-center justify-between gap-4"><a href="{{ $returnTo }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300">Cancel</a><x-admin.button>Save FAQ</x-admin.button></div>
    </form>
</x-admin.layout>
