<x-admin.layout
    title="Inquiry Details"
    :subtitle="'Customer inquiry #' . $inquiry->id . ' received on ' . $inquiry->created_at->format('d M Y, h:i A')"
>
    <div class="max-w-4xl space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800 dark:text-brand-400">
                ← Back to all inquiries
            </a>
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $inquiry->phone);
            @endphp
            <div class="flex items-center gap-3">
                <a href="tel:{{ $inquiry->phone }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    📞 Call Customer
                </a>
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi ' . $inquiry->name . ', thank you for contacting Kutch Tours & Taxi regarding ' . ($inquiry->service_title ?? 'travel services') . '.') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-[#25D366] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#20ba5a]">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    Chat on WhatsApp
                </a>
            </div>
        </div>

        {{-- Customer Info Card --}}
        <x-admin.card title="Customer Details">
            <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Customer Name</label>
                    <div class="text-base font-bold text-slate-900 dark:text-slate-100">{{ $inquiry->name }}</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Phone Number</label>
                    <div class="text-base font-bold text-slate-900 dark:text-slate-100">{{ $inquiry->phone }}</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Email Address</label>
                    <div class="text-sm text-slate-800 dark:text-slate-200">{{ $inquiry->email ?? 'Not provided' }}</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Date Received</label>
                    <div class="text-sm text-slate-800 dark:text-slate-200">{{ $inquiry->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </x-admin.card>

        {{-- Trip & Service Requirements Card --}}
        <x-admin.card title="Service & Trip Requirements">
            <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Service Requested</label>
                    <div class="text-base font-bold text-slate-900 dark:text-slate-100">
                        {{ $inquiry->service_title ?? ucfirst($inquiry->service_type) }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Service Type</label>
                    <div class="text-sm font-semibold uppercase tracking-wider text-brand-700 dark:text-brand-400">
                        {{ $inquiry->service_type }}
                    </div>
                </div>
                @if ($inquiry->travel_date)
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Intended Travel Date</label>
                    <div class="text-sm font-medium text-slate-800 dark:text-slate-200">
                        📅 {{ $inquiry->travel_date->format('l, d F Y') }}
                    </div>
                </div>
                @endif
                @if ($inquiry->passenger_count)
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Passengers</label>
                    <div class="text-sm font-medium text-slate-800 dark:text-slate-200">
                        👥 {{ $inquiry->passenger_count }} Persons
                    </div>
                </div>
                @endif
                @if ($inquiry->origin_name || $inquiry->destination_name)
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Route & Trip Details</label>
                    <div class="text-sm font-medium text-slate-800 dark:text-slate-200">
                        @if ($inquiry->origin_name && $inquiry->destination_name)
                            📍 {{ $inquiry->origin_name }} → {{ $inquiry->destination_name }}
                        @elseif ($inquiry->origin_name)
                            📍 Pickup / Origin: {{ $inquiry->origin_name }}
                        @else
                            📍 Destination: {{ $inquiry->destination_name }}
                        @endif
                        @if ($inquiry->trip_type)
                            ({{ str_replace('_', ' ', ucfirst($inquiry->trip_type)) }})
                        @endif
                    </div>
                </div>
                @endif
                @if ($inquiry->message)
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Customer Message / Note</label>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-200">
                        {{ $inquiry->message }}
                    </div>
                </div>
                @endif
            </div>
        </x-admin.card>

        {{-- Status & Admin Notes Card --}}
        <x-admin.card title="Manage Status & Follow-up Notes">
            <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}" class="space-y-5 p-5 sm:p-6">
                @csrf
                @method('PUT')
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">
                            Inquiry Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <option value="new" @selected($inquiry->status === 'new')>New (Uncontacted)</option>
                            <option value="contacted" @selected($inquiry->status === 'contacted')>Contacted / Proposal Sent</option>
                            <option value="closed" @selected($inquiry->status === 'closed')>Closed / Booked</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">
                            Last Contacted Date
                        </label>
                        <input type="text" readonly disabled value="{{ $inquiry->contacted_at ? $inquiry->contacted_at->format('d M Y, h:i A') : 'Not yet contacted' }}" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">
                        Internal Admin Notes
                    </label>
                    <textarea name="admin_notes" rows="3" placeholder="Add follow-up notes, negotiated price, preferred car..." class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ old('admin_notes', $inquiry->admin_notes) }}</textarea>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-800">
                        Update Inquiry
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-admin.layout>
