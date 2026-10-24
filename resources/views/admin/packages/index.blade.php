<x-admin.layout title="Tour Packages" subtitle="Manage public holiday packages, itineraries, tiered pricing, and inclusions.">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.packages.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">
            + Create tour package
        </a>
    </div>

    <x-admin.datatable
        id="packages"
        :data-url="route('admin.packages.data')"
        :columns="$columns"
        :rows="$packages"
        :default-order="[[5, 'asc']]"
        empty-title="No tour packages found"
        empty-description="Create your first public holiday tour package to showcase itineraries on the website."
    >
        <x-slot:label>Tour Packages</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="package_type" label="Package category" data-table-filter>
                <option value="all">All categories</option>
                <option value="Kutch" @selected(request('package_type') === 'Kutch')>Kutch Packages</option>
                <option value="Outstation" @selected(request('package_type') === 'Outstation')>Outstation Tours</option>
            </x-admin.select>
            <x-admin.select name="status" label="Publication status" data-table-filter>
                <option value="">All packages</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </x-admin.select>
            <x-admin.select name="featured" label="Featured status" data-table-filter>
                <option value="">All</option>
                <option value="yes" @selected(request('featured') === 'yes')>Featured</option>
                <option value="no" @selected(request('featured') === 'no')>Not featured</option>
            </x-admin.select>
        </x-slot:filters>

        @foreach ($packages as $package)
            <tr>
                @foreach ($columns as $definition)
                    <td class="px-5 py-5 text-slate-600 dark:text-slate-300">
                        @include('admin.packages.cell', ['column' => $definition['data']])
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-admin.datatable>

    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
