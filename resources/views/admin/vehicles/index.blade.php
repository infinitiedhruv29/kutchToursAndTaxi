<x-admin.layout title="Vehicles" subtitle="Manage the vehicle services offered to your customers.">
    <div class="mb-6 flex justify-end"><a href="{{ route('admin.vehicles.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">Add vehicle</a></div>
    <x-admin.datatable id="vehicles" :data-url="route('admin.vehicles.data')" :columns="$columns" :rows="$vehicles" :default-order="[[5, 'asc']]" empty-title="No vehicles found" empty-description="Add your first vehicle or adjust the search and filters.">
        <x-slot:label>Vehicles</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="status" label="Publication status" data-table-filter>
                <option value="">All vehicles</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </x-admin.select>
            <x-admin.select name="vehicle_type_id" label="Vehicle type" data-table-filter>
                <option value="">All types</option>@foreach ($types as $type)<option value="{{ $type->id }}" @selected((string) request('vehicle_type_id') === (string) $type->id)>{{ $type->name }}</option>@endforeach
            </x-admin.select>
            <x-admin.select name="vehicle_capacity_id" label="Passenger capacity" data-table-filter>
                <option value="">All capacities</option>@foreach ($capacities as $capacity)<option value="{{ $capacity->id }}" @selected((string) request('vehicle_capacity_id') === (string) $capacity->id)>{{ $capacity->label }}</option>@endforeach
            </x-admin.select>
        </x-slot:filters>
        @foreach ($vehicles as $vehicle)
            <tr>@foreach ($columns as $definition)<td class="px-5 py-5 text-slate-600 dark:text-slate-300">@include('admin.vehicles.cell', ['column' => $definition['data']])</td>@endforeach</tr>
        @endforeach
    </x-admin.datatable>
    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
