<x-admin.layout title="General FAQs" subtitle="One shared collection of answers for your customers.">
    <div class="mb-6 flex justify-end"><a href="{{ route('admin.faqs.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">Add FAQ</a></div>
    <x-admin.datatable id="faqs" :data-url="route('admin.faqs.data')" :columns="$columns" :rows="$faqs" :default-order="[[3, 'asc']]" empty-title="No FAQs found" empty-description="Add your first FAQ or adjust the search and publication filter.">
        <x-slot:label>General FAQs</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="status" label="Publication status" data-table-filter>
                <option value="">All FAQs</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </x-admin.select>
        </x-slot:filters>
        @foreach ($faqs as $faq)
            <tr>@foreach ($columns as $definition)<td class="px-5 py-5 text-slate-600 dark:text-slate-300">@include('admin.faqs.cell', ['column' => $definition['data']])</td>@endforeach</tr>
        @endforeach
    </x-admin.datatable>
    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
