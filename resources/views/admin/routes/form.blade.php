@php
    $editing = $taxiRoute->exists;
    $duration = $taxiRoute->estimated_duration_minutes;
    $vehicles = $vehicles ?? \App\Models\Vehicle::with(['type', 'capacity'])->where('is_published', true)->orderBy('sort_order')->get();
    $routeFares = $routeFares ?? ($taxiRoute->exists ? $taxiRoute->fares->keyBy('vehicle_id') : collect());
@endphp
<x-admin.layout :title="$editing ? 'Edit taxi route' : 'Create taxi route'" subtitle="Manage route details, fares, and publication.">
    <form method="POST" action="{{ $editing ? route('admin.routes.update', $taxiRoute) : route('admin.routes.store') }}" enctype="multipart/form-data" data-admin-form data-loading-text="Saving route…" data-route-form data-creating="{{ $editing ? 'false' : 'true' }}" class="space-y-6">
        @csrf
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        @if ($editing) @method('PUT') @endif
        <input type="hidden" name="auto_slug" value="{{ old('auto_slug', $editing ? '0' : '1') }}">
        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary><p class="font-semibold">Please check the highlighted fields.</p><ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></x-admin.alert>
        @endif

        <x-admin.card title="Route details" description="Origin, destination, title, and URL slug are required. Other information is optional.">
            <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-2">
                <x-admin.field name="origin_name" label="Origin" :value="$taxiRoute->origin_name" maxlength="255" required />
                <x-admin.field name="destination_name" label="Destination" :value="$taxiRoute->destination_name" maxlength="255" required />
                <x-admin.field name="title" label="Route title" :value="$taxiRoute->title" maxlength="255" required />
                <x-admin.field name="slug" label="URL slug" :value="$taxiRoute->slug" maxlength="255" pattern="[a-z0-9]+(-[a-z0-9]+)*" hint="Lowercase letters, numbers, and hyphens. An automatic suggestion is made for new routes." required />
                <x-admin.field name="distance_km" label="Distance (km)" type="number" :value="$taxiRoute->distance_km" min="0" max="99999999.99" step="0.01" />
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.field name="duration_hours" label="Duration: hours" type="number" :value="$duration === null ? null : intdiv($duration, 60)" min="0" max="71582788" step="1" />
                    <x-admin.field name="duration_minutes" label="Duration: minutes" type="number" :value="$duration === null ? null : $duration % 60" min="0" max="59" step="1" />
                </div>
            </div>
        </x-admin.card>

        <x-admin.card title="Descriptions">
            <div class="space-y-5 p-5 sm:p-6">
                <x-admin.textarea name="short_description" label="Short description" :value="$taxiRoute->short_description" maxlength="2000" hint="Plain text for route cards and summaries." />
                <input type="hidden" name="description" value="{{ old('description', $taxiRoute->description) }}">
            </div>
        </x-admin.card>

        <input type="hidden" name="image_alt" value="{{ old('image_alt', $taxiRoute->image_alt) }}">
        <input type="hidden" name="forward_one_way_fare" value="{{ old('forward_one_way_fare', $taxiRoute->forward_one_way_fare) }}">
        <input type="hidden" name="forward_round_trip_fare" value="{{ old('forward_round_trip_fare', $taxiRoute->forward_round_trip_fare) }}">
        <input type="hidden" name="reverse_enabled" value="{{ old('reverse_enabled', $taxiRoute->reverse_enabled ? '1' : '0') }}">
        <input type="hidden" name="reverse_one_way_fare" value="{{ old('reverse_one_way_fare', $taxiRoute->reverse_one_way_fare) }}">
        <input type="hidden" name="reverse_round_trip_fare" value="{{ old('reverse_round_trip_fare', $taxiRoute->reverse_round_trip_fare) }}">

        <x-admin.card title="Vehicle fleet rates" description="Vehicle fleet rates are managed under the Vehicles section. Select which vehicle is selected by default for this route.">
            <style>
                .vehicle-pricing-table, .vehicle-pricing-table th, .vehicle-pricing-table td:not(:has(input[type="number"])) {
                    user-select: none;
                    -webkit-user-select: none;
                }
                .vehicle-pricing-table ::selection {
                    background-color: rgba(220, 161, 52, 0.35) !important;
                    color: inherit !important;
                }
            </style>
            <div class="p-5 sm:p-6 overflow-x-auto vehicle-pricing-table">
                <table class="w-full text-left text-xs border-collapse select-none">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[0.7rem]">
                            <th class="py-2.5 px-3 font-semibold">Vehicle</th>
                            <th class="py-2.5 px-3 font-semibold">Capacity & Type</th>
                            <th class="py-2.5 px-3 font-semibold">Fleet Rates</th>
                            <th class="py-2.5 px-3 font-semibold text-center w-24">Default</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($vehicles as $index => $veh)
                            @php
                                $fare = $routeFares[$veh->id] ?? null;
                                $oneWayVal = old("vehicle_fares.{$index}.one_way_fare", $fare?->one_way_fare);
                                $roundTripVal = old("vehicle_fares.{$index}.round_trip_fare", $fare?->round_trip_fare);
                                $isDefault = old('default_vehicle_id', $fare?->is_default ? $veh->id : ($loop->first ? $veh->id : null)) == $veh->id;
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/40 transition-colors">
                                <td class="py-3 px-3">
                                    <input type="hidden" name="vehicle_fares[{{ $index }}][vehicle_id]" value="{{ $veh->id }}">
                                    <input type="hidden" name="vehicle_fares[{{ $index }}][one_way_fare]" value="{{ $oneWayVal }}">
                                    <input type="hidden" name="vehicle_fares[{{ $index }}][round_trip_fare]" value="{{ $roundTripVal }}">
                                    <label for="default_veh_{{ $veh->id }}" class="font-bold text-slate-900 dark:text-slate-100 block cursor-pointer hover:text-brand-600 dark:hover:text-brand-300 transition-colors select-none">
                                        {{ $veh->name }}
                                    </label>
                                </td>
                                <td class="py-3 px-3 select-none">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex rounded-full bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700 dark:text-slate-300">{{ $veh->capacity->label ?? '4 Seater' }}</span>
                                        <span class="inline-flex rounded-full bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700 dark:text-slate-300">{{ $veh->type->name ?? 'Cab' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400 text-[0.72rem] select-none">
                                    <span>₹{{ round($veh->price_per_km ?? 13) }}/km</span>
                                    @if ($veh->price_for_12_hrs)
                                        <span class="block text-[0.68rem] text-slate-400 dark:text-slate-500">₹{{ number_format((float) $veh->price_for_12_hrs) }} / 12 hrs</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <input type="radio"
                                           id="default_veh_{{ $veh->id }}"
                                           name="default_vehicle_id"
                                           value="{{ $veh->id }}"
                                           {{ $isDefault ? 'checked' : '' }}
                                           class="size-4 text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-700 cursor-pointer">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        <x-admin.card title="Visibility and order" description="Only published routes will be eligible for display on the future public website.">
            <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-3">
                <x-admin.checkbox name="is_published" label="Published" :checked="$taxiRoute->is_published" hint="Leave unchecked to save as a draft." />
                <x-admin.checkbox name="is_featured" label="Featured" :checked="$taxiRoute->is_featured" hint="Eligible for featured placement when published." />
                <x-admin.field name="sort_order" label="Display order" type="number" :value="$taxiRoute->sort_order ?? 0" min="0" max="4294967295" step="1" hint="Lower numbers appear first." />
            </div>
        </x-admin.card>

        <x-admin.card title="Search engine information">
            <div class="space-y-5 p-5 sm:p-6">
                <x-admin.field name="seo_title" label="SEO title" :value="$taxiRoute->seo_title" maxlength="255" />
                <x-admin.textarea name="seo_description" label="SEO description" :value="$taxiRoute->seo_description" maxlength="1000" />
            </div>
        </x-admin.card>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ $returnTo }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-brand-600">Back to routes</a>
            <x-admin.button>{{ $editing ? 'Save route' : 'Create route' }}</x-admin.button>
        </div>
    </form>
</x-admin.layout>
