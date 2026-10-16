<x-admin.layout :title="$module['title']" subtitle="Manage the choices available when configuring vehicles.">
    <div class="mb-6 flex justify-end">
        <a href="{{ route($module['prefix'].'.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">Add {{ strtolower($module['singular']) }}</a>
    </div>
    <x-admin.datatable :id="str_replace('.', '-', $module['prefix'])" :data-url="route($module['prefix'].'.data')" :columns="$columns" :rows="$records" :default-order="[[$module['capacity'] ? 3 : 2, 'asc']]" :empty-title="'No '.strtolower($module['title']).' found'" empty-description="Add your first record or adjust the search.">
        <x-slot:label>{{ $module['title'] }}</x-slot:label>
        @foreach ($records as $record)
            <tr>@foreach ($columns as $definition)<td class="px-5 py-5 text-slate-600 dark:text-slate-300">@include('admin.vehicle-lookups.cell', ['column' => $definition['data']])</td>@endforeach</tr>
        @endforeach
    </x-admin.datatable>
    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
