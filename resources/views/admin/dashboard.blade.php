<x-admin.layout title="Dashboard" subtitle="A clear view of your tours and taxi business.">
    <div class="mb-8 flex flex-col gap-5 rounded-2xl bg-slate-900 dark:bg-slate-950 p-6 text-white sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div><p class="text-xs font-medium uppercase tracking-widest text-brand-200">Your workspace</p><h2 class="mt-3 text-xl font-semibold">Welcome back, {{ auth()->user()->name }}.</h2><p class="mt-2 text-sm leading-6 text-slate-400">Here is where your business stands today.</p></div>
        <div class="flex items-center gap-4 rounded-xl border border-slate-700 px-5 py-4"><span class="flex size-10 items-center justify-center rounded-xl bg-brand-500/15 text-brand-200"><x-admin.icon name="inbox" /></span><div><p class="text-2xl font-semibold">{{ number_format($newInquiries) }}</p><p class="text-xs text-slate-400">New inquiries</p></div></div>
    </div>
    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-5">
        @foreach ($counts as $label => $count)
            <x-admin.card class="p-5"><p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p><p class="mt-3 text-3xl font-semibold tracking-tight">{{ number_format($count) }}</p><p class="mt-2 text-xs text-slate-400">Total records</p></x-admin.card>
        @endforeach
    </div>
    <x-admin.card title="Recent inquiries" description="The latest five customer inquiries received through your website.">
        @if ($recentInquiries->isEmpty())
            <x-admin.empty-state title="No inquiries yet" description="Customer inquiries will appear here when they are submitted through your website." />
        @else
            <x-admin.table>
                <x-slot:head><tr><th scope="col" class="px-6 py-4">Customer</th><th scope="col" class="px-6 py-4">Service</th><th scope="col" class="px-6 py-4">Status</th><th scope="col" class="whitespace-nowrap px-6 py-4">Received</th></tr></x-slot:head>
                @foreach ($recentInquiries as $inquiry)
                    <tr><td class="px-6 py-5 font-medium text-slate-900 dark:text-slate-100">{{ $inquiry->name }}</td><td class="px-6 py-5 text-slate-500 dark:text-slate-400">{{ $inquiry->service_title ?: ucfirst($inquiry->service_type) }}</td><td class="px-6 py-5"><span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-medium', 'bg-brand-50 dark:bg-brand-500/10 text-brand-800 dark:text-brand-200' => $inquiry->status === 'new', 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200' => $inquiry->status === 'contacted', 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' => $inquiry->status === 'closed'])>{{ ucfirst($inquiry->status) }}</span></td><td class="whitespace-nowrap px-6 py-5 text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Carbon::parse($inquiry->created_at)->format('d M Y, H:i') }}</td></tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>
</x-admin.layout>
