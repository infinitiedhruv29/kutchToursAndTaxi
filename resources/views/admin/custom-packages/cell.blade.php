@php($editUrl = route('admin.custom-packages.edit', [$customPackage, 'return_to' => route('admin.custom-packages.index', request()->only(['q', 'sort', 'direction', 'page', 'per_page']))]))
@switch($column)
    @case('client')
        <div>
            <a href="{{ $editUrl }}" data-return-link class="font-semibold text-slate-900 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-100 dark:hover:text-brand-200">
                {{ $customPackage->client_name }}
            </a>
            @if ($customPackage->client_phone)
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    <a href="tel:{{ $customPackage->client_phone }}" class="hover:underline">{{ $customPackage->client_phone }}</a>
                </p>
            @endif
        </div>
        @break
    @case('package_title')
        <div class="min-w-44 max-w-sm">
            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $customPackage->title }}</span>
            @if ($customPackage->highlight)
                <p class="mt-0.5 text-xs text-amber-600 dark:text-amber-400 font-medium">{{ $customPackage->highlight }}</p>
            @endif
        </div>
        @break
    @case('duration')
        <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
            {{ $customPackage->duration ?: 'Custom Duration' }}
        </span>
        @break
    @case('pricing')
        <div class="text-xs space-y-0.5 text-slate-600 dark:text-slate-300">
            @if ($customPackage->price_2p)
                <div><span class="text-slate-400">2P:</span> ₹{{ number_format((float) $customPackage->price_2p) }}</div>
            @endif
            @if ($customPackage->price_4p)
                <div><span class="text-slate-400">4P:</span> ₹{{ number_format((float) $customPackage->price_4p) }}</div>
            @endif
            @if ($customPackage->price_6p)
                <div><span class="text-slate-400">6P:</span> ₹{{ number_format((float) $customPackage->price_6p) }}</div>
            @endif
            @if (!$customPackage->price_2p && !$customPackage->price_4p && !$customPackage->price_6p)
                <span class="text-slate-400">Not quoted</span>
            @endif
        </div>
        @break
    @case('created_at')
        <span class="text-xs text-slate-500 dark:text-slate-400">
            {{ $customPackage->created_at?->format('d M, Y') }}
        </span>
        @break
    @case('actions')
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.custom-packages.pdf', $customPackage) }}" target="_blank" title="Download PDF Proposal" class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-red-300 bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700 shadow-xs hover:border-red-400 hover:bg-red-100 dark:border-red-700/80 dark:bg-red-950/60 dark:text-red-300 dark:hover:bg-red-900/60 dark:hover:text-red-100 transition-colors">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                <span>PDF</span>
            </a>
            <a href="{{ $editUrl }}" data-return-link class="rounded-lg px-2 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                Edit
            </a>
            <button
                type="button"
                data-confirm-delete
                data-action="{{ route('admin.custom-packages.destroy', $customPackage) }}"
                data-title="{{ $customPackage->title }} ({{ $customPackage->client_name }})"
                data-delete-title="Delete custom package proposal?"
                data-delete-warning="This proposal will be permanently removed."
                class="rounded-lg px-2 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/40"
            >
                Delete
            </button>
        </div>
        @break
@endswitch
