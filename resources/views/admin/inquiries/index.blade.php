<x-admin.layout title="Customer Inquiries" subtitle="Manage tour, taxi, and general customer booking inquiries.">
    <!-- Status Counters -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.inquiries.index') }}" class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-brand-500 transition-colors dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Inquiries</div>
            <div class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $statusCounts['total'] }}</div>
        </a>
        <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/50 shadow-sm hover:border-emerald-500 transition-colors dark:border-emerald-900/50 dark:bg-emerald-950/20">
            <div class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">New (Uncontacted)</div>
            <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">{{ $statusCounts['new'] }}</div>
        </a>
        <a href="{{ route('admin.inquiries.index', ['status' => 'contacted']) }}" class="p-4 rounded-xl border border-blue-200 bg-blue-50/50 shadow-sm hover:border-blue-500 transition-colors dark:border-blue-900/50 dark:bg-blue-950/20">
            <div class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wider">Contacted</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">{{ $statusCounts['contacted'] }}</div>
        </a>
        <a href="{{ route('admin.inquiries.index', ['status' => 'closed']) }}" class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 shadow-sm hover:border-slate-400 transition-colors dark:border-slate-800 dark:bg-slate-900/40">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Closed</div>
            <div class="text-2xl font-bold text-slate-700 dark:text-slate-400 mt-1">{{ $statusCounts['closed'] }}</div>
        </a>
    </div>

    <x-admin.datatable
        id="inquiries"
        :data-url="route('admin.inquiries.data')"
        :columns="$columns"
        :rows="$inquiries"
        :default-order="[[3, 'desc']]"
        empty-title="No inquiries found"
        empty-description="Customer inquiries from the website will appear here in real time."
    >
        <x-slot:label>Customer Inquiries</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="status" label="Inquiry status" data-table-filter>
                <option value="">All statuses</option>
                <option value="new" @selected(request('status') === 'new')>New</option>
                <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
                <option value="closed" @selected(request('status') === 'closed')>Closed</option>
            </x-admin.select>
            <x-admin.select name="service_type" label="Service type" data-table-filter>
                <option value="all">All services</option>
                <option value="general" @selected(request('service_type') === 'general')>General</option>
                <option value="taxi" @selected(request('service_type') === 'taxi')>Taxi / Cab</option>
                <option value="route" @selected(request('service_type') === 'route')>Taxi Route</option>
                <option value="package" @selected(request('service_type') === 'package')>Tour Package</option>
                <option value="custom" @selected(request('service_type') === 'custom')>Custom Proposal</option>
            </x-admin.select>
        </x-slot:filters>

        @foreach ($inquiries as $inquiry)
            <tr>
                @foreach ($columns as $definition)
                    <td class="px-5 py-5 text-slate-600 dark:text-slate-300">
                        @include('admin.inquiries.cell', ['column' => $definition['data']])
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-admin.datatable>

    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
