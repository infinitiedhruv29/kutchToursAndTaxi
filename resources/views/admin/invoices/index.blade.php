<x-admin.layout title="Invoices & Billing" subtitle="Create GST tax invoices, calculate taxi fares, and export PDF bills.">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.invoices.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">
            + New invoice
        </a>
    </div>

    <x-admin.datatable
        id="invoices"
        :data-url="route('admin.invoices.data')"
        :columns="$columns"
        :rows="$invoices"
        :default-order="[[2, 'desc']]"
        empty-title="No invoices yet"
        empty-description="Create your first client invoice or taxi billing receipt to export a professional PDF."
    >
        <x-slot:label>Tax Invoices</x-slot:label>
        @foreach ($invoices as $invoice)
            <tr>
                @foreach ($columns as $definition)
                    <td class="px-5 py-5 text-slate-600 dark:text-slate-300">
                        @include('admin.invoices.cell', ['column' => $definition['data']])
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-admin.datatable>

    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
