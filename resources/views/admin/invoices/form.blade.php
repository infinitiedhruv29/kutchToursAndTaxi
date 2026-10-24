<x-admin.layout
    :title="$invoice->exists ? 'Edit Invoice #' . $invoice->invoice_number : 'Create Tax Invoice'"
    subtitle="Generate customer GST invoice with automated taxi distance pricing and live total calculator."
>
    @php
        $packageItem = $invoice->items->firstWhere('item_type', 'package');
        $hasPackage = old('has_package', $invoice->exists ? (bool)$packageItem : false);
        $packageName = old('package_name', $packageItem?->description);
        $packagePrice = old('package_price', $packageItem?->amount);

        $taxiItem = $invoice->items->firstWhere('item_type', 'taxi');
        $hasTaxi = old('has_taxi', $invoice->exists ? (bool)$taxiItem : false);
        $vehicleId = old('vehicle_id', $taxiItem?->vehicle_id);
        $pricePerKm = old('price_per_km', $taxiItem?->price_per_km);
        $totalDistanceKm = old('total_distance_km', $taxiItem?->total_distance_km);

        $otherItems = $invoice->items->where('item_type', 'other')->values();
        $oldDescriptions = old('item_descriptions');
        $oldQuantities = old('item_quantities');
        $oldPrices = old('item_prices');

        $taxRate = (float) old('tax_rate', $invoice->exists ? $invoice->tax_rate : 5.0);
    @endphp

    <form
        method="POST"
        action="{{ $invoice->exists ? route('admin.invoices.update', $invoice) : route('admin.invoices.store') }}"
        data-admin-form
        data-loading-text="Processing invoice…"
        class="space-y-6 max-w-5xl"
    >
        @csrf
        @if ($invoice->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        <input type="hidden" name="submit_action" id="form-action" value="save">

        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary>
                {{ $errors->first() }}
            </x-admin.alert>
        @endif

        {{-- Invoice & Customer Info Card --}}
        <x-admin.card title="Customer & Invoice Details">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <x-admin.field
                    name="invoice_date"
                    label="Invoice Date"
                    type="date"
                    :value="old('invoice_date', $invoice->invoice_date?->format('Y-m-d') ?? date('Y-m-d'))"
                    required
                />
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Invoice Number
                    </label>
                    <input
                        type="text"
                        value="{{ $invoice->invoice_number }}"
                        readonly
                        disabled
                        class="min-h-11 w-full rounded-sm border border-slate-200 bg-slate-100 px-3.5 py-2.5 font-mono text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-400 cursor-not-allowed"
                    />
                    <p class="mt-1 text-xs text-slate-500">Auto-generated unique sequence.</p>
                </div>

                <x-admin.field
                    name="customer_name"
                    label="Customer / Company Name"
                    :value="old('customer_name', $invoice->customer_name)"
                    placeholder="e.g. Ramesh Patel or ABC Enterprises"
                    required
                    maxlength="255"
                />

                <x-admin.field
                    name="customer_contact"
                    label="Phone Number"
                    :value="old('customer_contact', $invoice->customer_contact)"
                    placeholder="e.g. +91 98765 43210"
                    hint="Optional 10-20 digit contact number."
                    maxlength="30"
                />

                <x-admin.field
                    name="customer_email"
                    label="Customer Email"
                    type="email"
                    :value="old('customer_email', $invoice->customer_email)"
                    placeholder="e.g. customer@example.com"
                    maxlength="255"
                />

                <x-admin.field
                    name="customer_gst_number"
                    label="Customer GSTIN (Optional)"
                    :value="old('customer_gst_number', $invoice->customer_gst_number)"
                    placeholder="e.g. 24ABCDE1234F1Z5"
                    maxlength="30"
                />

                <div class="sm:col-span-2">
                    <label for="customer_address" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Billing Address (Optional)
                    </label>
                    <textarea
                        id="customer_address"
                        name="customer_address"
                        rows="2"
                        placeholder="Street, City, State, PIN"
                        class="w-full rounded-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:border-brand-600 focus:outline-2 focus:outline-brand-100"
                    >{{ old('customer_address', $invoice->customer_address) }}</textarea>
                </div>
            </div>
        </x-admin.card>

        {{-- Tour Package Service Card --}}
        <x-admin.card title="Tour Package Service">
            <div class="p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        id="has_package"
                        name="has_package"
                        value="1"
                        @checked($hasPackage)
                        onchange="togglePackageSection(this.checked)"
                        class="size-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900"
                    />
                    <label for="has_package" class="text-sm font-semibold text-slate-800 dark:text-slate-200 cursor-pointer">
                        Include a Tour Package in this invoice
                    </label>
                </div>

                <div id="package-section" class="{{ $hasPackage ? '' : 'hidden' }} space-y-4 rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/30">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Pre-fill from existing package
                            </label>
                            <select
                                id="preset-package-select"
                                onchange="onSelectPresetPackage(this)"
                                class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                            >
                                <option value="">-- Choose package --</option>
                                @if (!empty($customPackages) && $customPackages->isNotEmpty())
                                    <optgroup label="Custom Proposals (Package Builder)">
                                        @foreach ($customPackages as $cp)
                                            <option value="{{ $cp->title }}" data-price="{{ $cp->price_2p }}">
                                                {{ $cp->title }} @if(!empty($cp->client_name))({{ $cp->client_name }})@endif - ₹{{ number_format((float)$cp->price_2p) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if (!empty($packages) && $packages->isNotEmpty())
                                    <optgroup label="Standard Tour Packages">
                                        @foreach ($packages as $pkg)
                                            <option value="{{ $pkg->title }}" data-price="{{ $pkg->price_2p }}">
                                                {{ $pkg->title }} - ₹{{ number_format((float)$pkg->price_2p) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                        <div class="sm:col-span-1">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Package Name / Description
                            </label>
                            <input
                                type="text"
                                id="package_name"
                                name="package_name"
                                value="{{ $packageName }}"
                                placeholder="e.g. 3 Days White Rann Special Package"
                                class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                            />
                        </div>
                        <div class="sm:col-span-1">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Package Price (₹)
                            </label>
                            <input
                                type="number"
                                id="package_price"
                                name="package_price"
                                step="0.01"
                                min="0"
                                value="{{ $packagePrice }}"
                                oninput="calculateTotals()"
                                placeholder="0.00"
                                class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 font-semibold"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>

        {{-- Taxi / Car Rental Service Card --}}
        <x-admin.card title="Taxi / Car Rental Service">
            <div class="p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        id="has_taxi"
                        name="has_taxi"
                        value="1"
                        @checked($hasTaxi)
                        onchange="toggleTaxiSection(this.checked)"
                        class="size-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900"
                    />
                    <label for="has_taxi" class="text-sm font-semibold text-slate-800 dark:text-slate-200 cursor-pointer">
                        Include Taxi / Cab Distance Rental
                    </label>
                </div>

                <div id="taxi-section" class="{{ $hasTaxi ? '' : 'hidden' }} space-y-4 rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/30">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Assigned Vehicle
                            </label>
                            <select
                                id="vehicle_id"
                                name="vehicle_id"
                                onchange="onSelectVehicle(this)"
                                class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                            >
                                <option value="">-- Select vehicle --</option>
                                @foreach ($vehicles as $v)
                                    <option
                                        value="{{ $v->id }}"
                                        data-price="{{ $v->price_per_km }}"
                                        @selected((int)$vehicleId === $v->id)
                                    >
                                        {{ $v->name }} ({{ $v->type?->name ?? 'Cab' }} - ₹{{ number_format((float)$v->price_per_km, 2) }}/km)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Price Per KM (₹)
                            </label>
                            <input
                                type="number"
                                id="price_per_km"
                                name="price_per_km"
                                step="0.01"
                                min="0"
                                value="{{ $pricePerKm }}"
                                oninput="calculateTotals()"
                                placeholder="e.g. 14.00"
                                class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 font-semibold"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Total Distance Traveled (KM)
                            </label>
                            <input
                                type="number"
                                id="total_distance_km"
                                name="total_distance_km"
                                step="0.01"
                                min="0"
                                value="{{ $totalDistanceKm }}"
                                oninput="calculateTotals()"
                                placeholder="e.g. 350"
                                class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 font-semibold"
                            />
                        </div>
                    </div>
                    <div class="flex justify-end pt-2 text-xs font-semibold text-slate-600 dark:text-slate-300">
                        <span>Taxi Subtotal: ₹<span id="taxi-calculated-subtotal">0.00</span></span>
                    </div>
                </div>
            </div>
        </x-admin.card>

        {{-- Additional Line Items Repeater --}}
        <x-admin.card title="Additional Line Items (Tolls, Parking, Permits, Guide, etc.)">
            <div class="p-5 sm:p-6 space-y-4">
                <div id="line-items-container" class="space-y-3">
                    {{-- Render existing items if any --}}
                    @php
                        $itemsToRender = [];
                        if (is_array($oldDescriptions)) {
                            foreach ($oldDescriptions as $idx => $desc) {
                                $itemsToRender[] = [
                                    'description' => $desc,
                                    'quantity' => $oldQuantities[$idx] ?? 1,
                                    'unit_price' => $oldPrices[$idx] ?? 0,
                                ];
                            }
                        } elseif ($otherItems->isNotEmpty()) {
                            foreach ($otherItems as $ot) {
                                $itemsToRender[] = [
                                    'description' => $ot->description,
                                    'quantity' => $ot->quantity,
                                    'unit_price' => $ot->unit_price,
                                ];
                            }
                        }
                    @endphp

                    @forelse ($itemsToRender as $item)
                        <div class="line-item-row flex flex-wrap items-center gap-3 rounded-lg border border-slate-200 bg-slate-50/50 p-3 dark:border-slate-800 dark:bg-slate-900/30">
                            <div class="flex-1 min-w-[200px]">
                                <input
                                    type="text"
                                    name="item_descriptions[]"
                                    value="{{ $item['description'] }}"
                                    placeholder="Item description (e.g. Toll taxes & Parking charges)"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                />
                            </div>
                            <div class="w-24">
                                <input
                                    type="number"
                                    name="item_quantities[]"
                                    step="0.01"
                                    min="0"
                                    value="{{ $item['quantity'] }}"
                                    oninput="calculateTotals()"
                                    placeholder="Qty"
                                    class="row-qty w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 text-center"
                                />
                            </div>
                            <div class="w-32">
                                <input
                                    type="number"
                                    name="item_prices[]"
                                    step="0.01"
                                    min="0"
                                    value="{{ $item['unit_price'] }}"
                                    oninput="calculateTotals()"
                                    placeholder="Rate (₹)"
                                    class="row-price w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 font-semibold"
                                />
                            </div>
                            <div class="w-24 text-right text-xs font-semibold text-slate-700 dark:text-slate-300">
                                ₹<span class="row-total">0.00</span>
                            </div>
                            <button
                                type="button"
                                onclick="removeLineItem(this)"
                                title="Remove line item"
                                class="rounded-lg p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/30 dark:hover:text-red-400"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @empty
                        {{-- Empty initial row for convenience --}}
                    @endforelse
                </div>

                <button
                    type="button"
                    onclick="addLineItem()"
                    class="inline-flex items-center gap-2 rounded-xl border border-dashed border-slate-300 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:border-brand-600 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300"
                >
                    + Add Additional Item
                </button>
            </div>
        </x-admin.card>

        {{-- Tax, Notes & Invoice Totals Summary Card --}}
        <x-admin.card title="Tax Calculation & Summary">
            <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-6">
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            GST Tax Rate
                        </label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach ([0 => '0% (Exempt)', 5 => '5% GST', 12 => '12% GST', 18 => '18% GST'] as $val => $label)
                                <label class="flex cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-xs font-medium text-slate-800 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                    <input
                                        type="radio"
                                        name="tax_rate"
                                        value="{{ $val }}"
                                        @checked($taxRate == $val)
                                        onchange="calculateTotals()"
                                        class="mr-2 text-brand-600 focus:ring-brand-500"
                                    />
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Invoice Notes / Payment Instructions
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="e.g. Bank: State Bank of India | A/C: 1234567890 | IFSC: SBIN0001234. Payment due within 7 days."
                            class="w-full rounded-xl border border-slate-300 bg-white p-3 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                        >{{ old('notes', $invoice->notes) }}</textarea>
                    </div>
                </div>

                {{-- Live Totals Breakdown --}}
                <div class="flex flex-col justify-center rounded-xl border border-brand-200 bg-brand-50/40 p-5 dark:border-brand-900/40 dark:bg-brand-950/20">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>Taxable Subtotal:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">₹<span id="summary-subtotal">0.00</span></span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>GST (<span id="summary-tax-rate">{{ $taxRate }}</span>%):</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">₹<span id="summary-tax-amount">0.00</span></span>
                        </div>
                        <div class="border-t border-brand-200 dark:border-brand-900/60 pt-3">
                            <div class="flex justify-between text-base font-bold text-brand-900 dark:text-brand-300">
                                <span>Grand Total:</span>
                                <span>₹<span id="summary-grand-total">0.00</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>

        {{-- Form Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-4 pt-4">
            <a href="{{ $returnTo }}" class="rounded-lg px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300 dark:hover:bg-slate-800">
                Cancel
            </a>
            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    onclick="document.getElementById('form-action').value='save';"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    Save Invoice
                </button>
                <button
                    type="submit"
                    onclick="document.getElementById('form-action').value='export';"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Save & Download PDF
                </button>
            </div>
        </div>
    </form>

    <script>
        function togglePackageSection(checked) {
            const sec = document.getElementById('package-section');
            if (checked) {
                sec.classList.remove('hidden');
            } else {
                sec.classList.add('hidden');
            }
            calculateTotals();
        }

        function toggleTaxiSection(checked) {
            const sec = document.getElementById('taxi-section');
            if (checked) {
                sec.classList.remove('hidden');
            } else {
                sec.classList.add('hidden');
            }
            calculateTotals();
        }

        function onSelectPresetPackage(select) {
            const selectedOpt = select.options[select.selectedIndex];
            if (select.value) {
                document.getElementById('package_name').value = select.value;
                const price = selectedOpt.getAttribute('data-price');
                if (price) {
                    document.getElementById('package_price').value = parseFloat(price).toFixed(2);
                }
                calculateTotals();
            }
        }

        function onSelectVehicle(select) {
            const selectedOpt = select.options[select.selectedIndex];
            const price = selectedOpt.getAttribute('data-price');
            if (price) {
                document.getElementById('price_per_km').value = parseFloat(price).toFixed(2);
                calculateTotals();
            }
        }

        function addLineItem() {
            const container = document.getElementById('line-items-container');
            const row = document.createElement('div');
            row.className = 'line-item-row flex flex-wrap items-center gap-3 rounded-lg border border-slate-200 bg-slate-50/50 p-3 dark:border-slate-800 dark:bg-slate-900/30';
            row.innerHTML = `
                <div class="flex-1 min-w-[200px]">
                    <input
                        type="text"
                        name="item_descriptions[]"
                        placeholder="Item description (e.g. Toll taxes & Parking charges)"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                    />
                </div>
                <div class="w-24">
                    <input
                        type="number"
                        name="item_quantities[]"
                        step="0.01"
                        min="0"
                        value="1"
                        oninput="calculateTotals()"
                        placeholder="Qty"
                        class="row-qty w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 text-center"
                    />
                </div>
                <div class="w-32">
                    <input
                        type="number"
                        name="item_prices[]"
                        step="0.01"
                        min="0"
                        value="0.00"
                        oninput="calculateTotals()"
                        placeholder="Rate (₹)"
                        class="row-price w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 font-semibold"
                    />
                </div>
                <div class="w-24 text-right text-xs font-semibold text-slate-700 dark:text-slate-300">
                    ₹<span class="row-total">0.00</span>
                </div>
                <button
                    type="button"
                    onclick="removeLineItem(this)"
                    title="Remove line item"
                    class="rounded-lg p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/30 dark:hover:text-red-400"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            `;
            container.appendChild(row);
            calculateTotals();
        }

        function removeLineItem(btn) {
            btn.closest('.line-item-row').remove();
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;

            // Package calculation
            const hasPkg = document.getElementById('has_package').checked;
            if (hasPkg) {
                const pkgPrice = parseFloat(document.getElementById('package_price').value) || 0;
                subtotal += pkgPrice;
            }

            // Taxi calculation
            const hasTaxi = document.getElementById('has_taxi').checked;
            if (hasTaxi) {
                const pricePerKm = parseFloat(document.getElementById('price_per_km').value) || 0;
                const distKm = parseFloat(document.getElementById('total_distance_km').value) || 0;
                const taxiTotal = pricePerKm * distKm;
                const taxiSpan = document.getElementById('taxi-calculated-subtotal');
                if (taxiSpan) {
                    taxiSpan.textContent = taxiTotal.toFixed(2);
                }
                subtotal += taxiTotal;
            }

            // Additional line items
            const rows = document.querySelectorAll('.line-item-row');
            rows.forEach(row => {
                const qtyInput = row.querySelector('.row-qty');
                const priceInput = row.querySelector('.row-price');
                const totalSpan = row.querySelector('.row-total');

                const qty = parseFloat(qtyInput ? qtyInput.value : 1) || 0;
                const rate = parseFloat(priceInput ? priceInput.value : 0) || 0;
                const rowAmount = qty * rate;

                if (totalSpan) {
                    totalSpan.textContent = rowAmount.toFixed(2);
                }
                subtotal += rowAmount;
            });

            // Tax calculation
            const taxRadios = document.querySelectorAll('input[name="tax_rate"]:checked');
            let taxRate = 0;
            if (taxRadios.length > 0) {
                taxRate = parseFloat(taxRadios[0].value) || 0;
            }
            const taxAmount = subtotal * (taxRate / 100);
            const grandTotal = subtotal + taxAmount;

            document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('summary-tax-rate').textContent = taxRate;
            document.getElementById('summary-tax-amount').textContent = taxAmount.toFixed(2);
            document.getElementById('summary-grand-total').textContent = grandTotal.toFixed(2);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function () {
            calculateTotals();
        });
    </script>
</x-admin.layout>
