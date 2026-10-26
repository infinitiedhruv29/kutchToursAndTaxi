<x-admin.layout title="Destinations" subtitle="Manage lightweight destination cards without separate detail pages.">
    <div class="mb-6 flex justify-end"><a href="{{ route('admin.destinations.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">Add destination</a></div>
    <x-admin.datatable id="destinations" :data-url="route('admin.destinations.data')" :columns="$columns" :rows="$destinations" :default-order="[[2, 'asc']]" empty-title="No destinations found" empty-description="Add your first destination or adjust the search and filters.">
        <x-slot:label>Destinations</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="status" label="Publication status" data-table-filter>
                <option value="">All destinations</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </x-admin.select>
            <x-admin.select name="featured" label="Featured status" data-table-filter>
                <option value="">All</option><option value="yes" @selected(request('featured') === 'yes')>Featured</option><option value="no" @selected(request('featured') === 'no')>Not featured</option>
            </x-admin.select>
        </x-slot:filters>
        @foreach ($destinations as $destination)
            <tr>@foreach ($columns as $definition)<td class="px-5 py-5 text-slate-600 dark:text-slate-300">@include('admin.destinations.cell', ['column' => $definition['data']])</td>@endforeach</tr>
        @endforeach
    </x-admin.datatable>
    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
