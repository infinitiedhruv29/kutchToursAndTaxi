@switch($column)
    @case('customer')
        <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $inquiry->name }}</div>
        <div class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
            <span>📞 {{ $inquiry->phone }}</span>
            @if ($inquiry->email)
                <span>✉️ {{ $inquiry->email }}</span>
            @endif
        </div>
        @break

    @case('service')
        <div class="text-sm font-medium text-slate-800 dark:text-slate-200">
            {{ $inquiry->service_title ?? ucfirst($inquiry->service_type) }}
        </div>
        <div class="text-xs text-slate-500 mt-0.5 line-clamp-1">
            @if ($inquiry->travel_date)
                <span class="inline-block mr-2 font-medium">📅 {{ $inquiry->travel_date->format('d M Y') }}</span>
            @endif
            @if ($inquiry->passenger_count)
                <span class="inline-block mr-2">👥 {{ $inquiry->passenger_count }} Pax</span>
            @endif
            @if ($inquiry->origin_name && $inquiry->destination_name)
                <span>📍 {{ $inquiry->origin_name }} → {{ $inquiry->destination_name }}</span>
            @endif
            @if ($inquiry->message)
                <span class="italic text-slate-400">"{{ Str::limit($inquiry->message, 50) }}"</span>
            @endif
        </div>
        @break

    @case('status')
        @php
            $badges = [
                'new' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-300',
                'contacted' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border-blue-300',
                'closed' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border-slate-300',
            ];
            $class = $badges[$inquiry->status] ?? $badges['new'];
        @endphp
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider border {{ $class }}">
            {{ ucfirst($inquiry->status) }}
        </span>
        @break

    @case('date')
        <div class="text-sm text-slate-700 dark:text-slate-300">
            {{ $inquiry->created_at->format('d M Y') }}
        </div>
        <div class="text-xs text-slate-400">
            {{ $inquiry->created_at->format('h:i A') }}
        </div>
        @break

    @case('actions')
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="rounded-lg px-2 py-2 text-sm font-medium text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-brand-200">View<span class="sr-only"> {{ $inquiry->name }}</span></a>
            <button type="button" data-confirm-delete data-action="{{ route('admin.inquiries.destroy', $inquiry) }}" data-title="{{ $inquiry->name }}" data-delete-title="Delete inquiry?" data-delete-warning="This will permanently delete this inquiry record." class="rounded-lg px-2 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus-visible:outline-2 focus-visible:outline-red-600 dark:text-red-300 dark:hover:bg-red-950/40">Delete<span class="sr-only"> {{ $inquiry->name }}</span></button>
        </div>
        @break
@endswitch
