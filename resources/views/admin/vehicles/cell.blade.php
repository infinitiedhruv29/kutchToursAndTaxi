@php($editUrl = route('admin.vehicles.edit', [$vehicle, 'return_to' => route('admin.vehicles.index', request()->only(['q', 'status', 'vehicle_type_id', 'vehicle_capacity_id', 'sort', 'direction', 'page', 'per_page']))]))
@switch($column)
    @case('vehicle')
        <div class="flex min-w-48 items-center gap-3">
            @if ($vehicle->image_path)<img src="{{ Storage::disk('public')->url($vehicle->image_path) }}" alt="{{ $vehicle->image_alt ?? '' }}" class="size-12 shrink-0 rounded-lg object-cover" loading="lazy">
            @else<span class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800"><x-admin.icon name="route" /></span>@endif
            <a href="{{ $editUrl }}" data-return-link class="rounded-sm font-semibold text-slate-900 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-100 dark:hover:text-brand-200">{{ $vehicle->name }}</a>
        </div>
        @break
    @case('type') {{ $vehicle->type->name }} @break
    @case('capacity')
        <p>{{ $vehicle->capacity->label }}</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $vehicle->capacity->passenger_capacity }} passengers, excluding driver</p>
        @break
    @case('pricing')
        <p class="whitespace-nowrap">{{ $vehicle->price_per_km === null ? 'Price on inquiry' : '₹'.number_format((float) $vehicle->price_per_km, 2).'/km' }}</p>
        @if ($vehicle->min_km_per_day !== null)<p class="mt-1 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">Minimum {{ $vehicle->min_km_per_day }} km/day</p>@endif
        @break
    @case('visibility')
        <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-medium', 'bg-brand-50 text-brand-800 dark:bg-brand-500/10 dark:text-brand-200' => $vehicle->is_published, 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' => !$vehicle->is_published])>{{ $vehicle->is_published ? 'Published' : 'Draft' }}</span>
        @break
    @case('sort_order') {{ $vehicle->sort_order }} @break
    @case('actions')
        <x-admin.record-actions :edit-url="$editUrl" :delete-url="route('admin.vehicles.destroy', $vehicle)" :name="$vehicle->name" delete-title="Delete vehicle?" />
        @break
@endswitch
