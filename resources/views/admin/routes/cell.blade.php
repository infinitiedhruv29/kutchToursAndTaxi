@switch($column)
    @case('route')
        <div class="flex min-w-52 items-center gap-3">
            @if ($taxiRoute->image_path)
                <img src="{{ Storage::disk('public')->url($taxiRoute->image_path) }}" alt="{{ $taxiRoute->image_alt ?? '' }}" class="size-12 shrink-0 rounded-lg object-cover" loading="lazy">
            @else
                <span class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400"><x-admin.icon name="route" /></span>
            @endif
            <div>
                <a href="{{ route('admin.routes.edit', ['taxiRoute' => $taxiRoute, 'return_to' => route('admin.routes.index', request()->only(['q', 'status', 'sort', 'direction', 'page', 'per_page']))]) }}" data-return-link class="rounded-sm font-semibold text-slate-900 dark:text-slate-100 hover:text-brand-700 dark:hover:text-brand-200 focus-visible:outline-2 focus-visible:outline-brand-600">{{ $taxiRoute->title }}</a>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $taxiRoute->origin_name }} → {{ $taxiRoute->destination_name }}</p>
            </div>
        </div>
        @break
    @case('fares')
        <div class="whitespace-nowrap text-xs leading-5 text-slate-500 dark:text-slate-400">
            @if ($taxiRoute->fares->isNotEmpty())
                <div class="flex flex-col gap-1 max-w-[220px]">
                    @foreach ($taxiRoute->fares->take(3) as $fare)
                        @php
                            $fleetDisplay = $fare->vehicle?->price_for_12_hrs
                                ? '₹' . number_format((float) $fare->vehicle->price_for_12_hrs)
                                : ($fare->round_trip_fare !== null ? '₹' . number_format((float) $fare->round_trip_fare) : ($fare->one_way_fare !== null ? '₹' . number_format((float) $fare->one_way_fare) : 'On inquiry'));
                        @endphp
                        <div class="flex items-center justify-between text-[0.72rem] bg-slate-50 dark:bg-slate-900/60 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-800">
                            <span class="font-medium text-slate-700 dark:text-slate-300 truncate mr-1.5">{{ $fare->vehicle->name ?? 'Vehicle' }}</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100 shrink-0">{{ $fleetDisplay }}</span>
                        </div>
                    @endforeach
                    @if ($taxiRoute->fares->count() > 3)
                        <span class="text-[0.68rem] text-brand-600 dark:text-brand-400 font-medium">+{{ $taxiRoute->fares->count() - 3 }} more vehicles</span>
                    @endif
                </div>
            @else
                <p>One-way: <span class="font-medium text-slate-800 dark:text-slate-200">{{ $taxiRoute->forward_one_way_fare === null ? 'Price on inquiry' : '₹'.number_format((float) $taxiRoute->forward_one_way_fare, 2) }}</span></p>
                <p>Round-trip: <span class="font-medium text-slate-800 dark:text-slate-200">{{ $taxiRoute->forward_round_trip_fare === null ? 'Price on inquiry' : '₹'.number_format((float) $taxiRoute->forward_round_trip_fare, 2) }}</span></p>
            @endif
        </div>
        @break
    @case('reverse')
        {{ $taxiRoute->reverse_enabled ? 'Enabled' : 'Disabled' }}
        @break
    @case('visibility')
        <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-medium', 'bg-brand-50 dark:bg-brand-500/10 text-brand-800 dark:text-brand-200' => $taxiRoute->is_published, 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' => !$taxiRoute->is_published])>{{ $taxiRoute->is_published ? 'Published' : 'Draft' }}</span>
        @if ($taxiRoute->is_featured)<span class="mt-1 block text-xs text-amber-700 dark:text-amber-300">Featured</span>@endif
        @break
    @case('display_order')
        {{ $taxiRoute->sort_order }}
        @break
    @case('actions')
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.routes.edit', ['taxiRoute' => $taxiRoute, 'return_to' => route('admin.routes.index', request()->only(['q', 'status', 'sort', 'direction', 'page', 'per_page']))]) }}" data-return-link class="rounded-lg px-2 py-2 font-medium text-brand-700 dark:text-brand-200 focus-visible:outline-2 focus-visible:outline-brand-600">Edit<span class="sr-only"> {{ $taxiRoute->title }}</span></a>
            <button type="button" data-delete-route data-action="{{ route('admin.routes.destroy', $taxiRoute) }}" data-title="{{ $taxiRoute->title }}" class="rounded-lg px-2 py-2 font-medium text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-950/40 focus-visible:outline-2 focus-visible:outline-red-600">Delete<span class="sr-only"> {{ $taxiRoute->title }}</span></button>
        </div>
        @break
@endswitch
