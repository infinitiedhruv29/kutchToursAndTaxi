<x-admin.layout title="Taxi routes" subtitle="Manage the journeys your taxi business offers.">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.routes.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center justify-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">Add route</a>
    </div>
    <x-admin.datatable id="taxi-routes" :data-url="route('admin.routes.data')" :columns="$columns" :rows="$routes" :default-order="[[4, 'asc']]" empty-title="No routes found" empty-description="Add your first taxi route, or adjust the search and publication filters.">
        <x-slot:label>Taxi routes</x-slot:label>
        <x-slot:filters>
            <div>
                <label for="taxi-routes-status" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Publication status</label>
                <select id="taxi-routes-status" name="status" data-table-filter class="min-h-11 rounded-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm focus:outline-2 focus:outline-brand-600">
                    <option value="">All routes</option>
                    <option value="published" @selected(($filters['status'] ?? '') === 'published')>Published</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                </select>
            </div>
        </x-slot:filters>
        @foreach ($routes as $taxiRoute)
            <tr>
                @foreach ($columns as $definition)
                    <td class="px-5 py-5 text-slate-600 dark:text-slate-300">@include('admin.routes.cell', ['column' => $definition['data']])</td>
                @endforeach
            </tr>
        @endforeach
    </x-admin.datatable>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use the deletion confirmation dialog.</p></noscript>
    <p data-route-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-700 dark:text-red-300"></p>
</x-admin.layout>
