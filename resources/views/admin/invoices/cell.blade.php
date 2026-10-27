@php($editUrl = route('admin.invoices.edit', [$invoice, 'return_to' => route('admin.invoices.index', request()->only(['q', 'sort', 'direction', 'page', 'per_page']))]))
@switch($column)
    @case('invoice_number')
        <div>
            <a href="{{ $editUrl }}" data-return-link class="font-mono font-bold text-brand-700 hover:text-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-brand-300">
                {{ $invoice->invoice_number }}
            </a>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                {{ $invoice->invoice_date?->format('d M, Y') }}
            </p>
        </div>
        @break
    @case('customer')
        <div>
            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->customer_name }}</span>
            @if ($invoice->customer_contact)
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    <a href="tel:{{ $invoice->customer_contact }}" class="hover:underline">{{ $invoice->customer_contact }}</a>
                </p>
            @endif
        </div>
        @break
    @case('invoice_date')
        <span class="text-xs text-slate-600 dark:text-slate-300">
            {{ $invoice->invoice_date?->format('d M, Y') }}
        </span>
        @break
    @case('items_count')
        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
            {{ $invoice->items_count ?? $invoice->items->count() }} {{ Str::plural('item', $invoice->items_count ?? $invoice->items->count()) }}
        </span>
        @break
    @case('total_amount')
        <div>
            <span class="font-bold text-slate-900 dark:text-slate-100">
                ₹{{ number_format((float) $invoice->total_amount, 2) }}
            </span>
            @if ((float) $invoice->tax_rate > 0)
                <span class="block text-xs text-emerald-600 dark:text-emerald-400">
                    Incl. {{ (float) $invoice->tax_rate }}% GST
                </span>
            @endif
        </div>
        @break
    @case('actions')
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.invoices.pdf', $invoice) }}" target="_blank" title="Download PDF Invoice" class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-red-300 bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700 shadow-xs hover:border-red-400 hover:bg-red-100 dark:border-red-700/80 dark:bg-red-950/60 dark:text-red-300 dark:hover:bg-red-900/60 dark:hover:text-red-100 transition-colors">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                <span>PDF</span>
            </a>
            <a href="{{ $editUrl }}" data-return-link class="rounded-lg px-2 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                Edit
            </a>
            <button
                type="button"
                data-confirm-delete
                data-action="{{ route('admin.invoices.destroy', $invoice) }}"
                data-title="{{ $invoice->invoice_number }} ({{ $invoice->customer_name }})"
                data-delete-title="Delete invoice?"
                data-delete-warning="This invoice and all its line items will be permanently removed."
                class="rounded-lg px-2 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/40"
            >
                Delete
            </button>
        </div>
        @break
@endswitch
