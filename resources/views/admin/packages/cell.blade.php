@switch($column)
    @case('package')
        <div class="flex items-center gap-3">
            @if ($package->image_path)
                @php
                    $imgSrc = str_starts_with($package->image_path, 'packages/')
                        ? asset('storage/' . $package->image_path)
                        : asset($package->image_path);
                @endphp
                <img src="{{ $imgSrc }}" alt="{{ $package->title }}" class="h-10 w-14 rounded-lg object-cover border border-slate-200 dark:border-slate-800 shrink-0">
            @else
                <div class="h-10 w-14 rounded-lg bg-slate-100 flex items-center justify-center text-xs text-slate-400 dark:bg-slate-800 shrink-0">No img</div>
            @endif
            <div>
                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span>{{ $package->title }}</span>
                    @if ($package->is_featured)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.65rem] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                            ★ Featured
                        </span>
                    @endif
                </div>
                <div class="text-xs text-slate-500 font-mono mt-0.5">/package-{{ $package->slug }}</div>
            </div>
        </div>
        @break

    @case('type')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $package->package_type === 'Outstation' ? 'bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300' : 'bg-brand-50 text-brand-800 dark:bg-brand-950/60 dark:text-brand-300' }}">
            {{ $package->package_type ?? 'Kutch' }}
        </span>
        @break

    @case('duration')
        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $package->duration ?? 'Custom' }}</span>
        @break

    @case('pricing')
        <div class="text-xs text-slate-700 dark:text-slate-300 space-y-0.5">
            @if ($package->price_2p)
                <div><span class="text-slate-400">2P:</span> <strong>₹{{ number_format($package->price_2p) }}</strong></div>
            @endif
            @if ($package->price_4p)
                <div><span class="text-slate-400">4P:</span> ₹{{ number_format($package->price_4p) }}</div>
            @endif
            @if ($package->price_6p)
                <div><span class="text-slate-400">6P:</span> ₹{{ number_format($package->price_6p) }}</div>
            @endif
            @if (! $package->price_2p && ! $package->price_4p)
                <span class="text-slate-400 italic">Custom Quote</span>
            @endif
        </div>
        @break

    @case('status')
        @if ($package->is_published)
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                Published
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                Draft
            </span>
        @endif
        @break

    @case('sort_order')
        <span class="text-xs font-medium text-slate-500">{{ $package->sort_order }}</span>
        @break

    @case('actions')
        @php($editUrl = route('admin.packages.edit', ['package' => $package, 'return_to' => request()->fullUrl()]))
        <div class="flex items-center gap-2">
            <a href="{{ route('package.show', $package->slug) }}" target="_blank" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-300" title="View on website">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
            <x-admin.record-actions :edit-url="$editUrl" :delete-url="route('admin.packages.destroy', $package)" :name="$package->title" delete-title="Delete package?" />
        </div>
        @break
@endswitch
