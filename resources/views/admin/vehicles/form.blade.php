<x-admin.layout :title="$vehicle->exists ? 'Edit vehicle' : 'Create vehicle'" subtitle="Configure a service/model offering, not an individual registered taxi.">
    @if ($types->isEmpty() || $capacities->isEmpty())
        <x-admin.card class="mb-6 space-y-3 p-5">
            <p class="font-semibold">Set up vehicle types and passenger capacities first.</p>
            <p class="text-sm text-slate-500 dark:text-slate-400">Every vehicle needs both choices before it can be saved.</p>
            <div class="flex flex-wrap gap-4 text-sm font-medium text-brand-700 dark:text-brand-200"><a href="{{ route('admin.vehicle-types.index') }}" class="rounded-sm underline focus-visible:outline-2">Manage vehicle types</a><a href="{{ route('admin.vehicle-capacities.index') }}" class="rounded-sm underline focus-visible:outline-2">Manage passenger capacities</a></div>
        </x-admin.card>
    @endif
    <form method="POST" action="{{ $vehicle->exists ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}" enctype="multipart/form-data" data-admin-form data-vehicle-form data-creating="{{ $vehicle->exists ? 'false' : 'true' }}" data-loading-text="Saving vehicle…" class="space-y-6">
        @csrf @if ($vehicle->exists) @method('PUT') @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        <input type="hidden" name="auto_slug" value="{{ old('auto_slug', $vehicle->exists ? '0' : '1') }}">
        @if ($errors->any())<x-admin.alert type="error" data-validation-summary>Please check the highlighted fields.</x-admin.alert>@endif
        <x-admin.card title="Vehicle details">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <x-admin.field name="name" label="Vehicle name" :value="$vehicle->name" maxlength="255" required hint="For example, Toyota Innova or similar." />
                <x-admin.field name="slug" label="URL slug" :value="$vehicle->slug" maxlength="255" pattern="[a-z0-9]+(-[a-z0-9]+)*" required hint="Automatically suggested for new vehicles; existing slugs are not changed when renaming." />
                <x-admin.select name="vehicle_type_id" label="Vehicle type" required>
                    <option value="">Choose a type</option>@foreach ($types as $type)<option value="{{ $type->id }}" @selected((string) old('vehicle_type_id', $vehicle->vehicle_type_id) === (string) $type->id)>{{ $type->name }}</option>@endforeach
                </x-admin.select>
                <x-admin.select name="vehicle_capacity_id" label="Passenger capacity (excluding driver)" required>
                    <option value="">Choose a capacity</option>@foreach ($capacities as $capacity)<option value="{{ $capacity->id }}" @selected((string) old('vehicle_capacity_id', $vehicle->vehicle_capacity_id) === (string) $capacity->id)>{{ $capacity->label }} — {{ $capacity->passenger_capacity }} passengers</option>@endforeach
                </x-admin.select>
            </div>
        </x-admin.card>
        <x-admin.card title="Pricing and notes" description="All fields in this section are optional, including for published vehicles.">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <x-admin.field name="price_per_km" label="Price per km (₹)" type="number" :value="$vehicle->price_per_km" min="0" max="9999999999.99" step="0.01" hint="Leave blank for Price on inquiry. Zero is an explicit price." />
                <x-admin.field name="min_km_per_day" label="Minimum km per day" type="number" :value="$vehicle->min_km_per_day" min="0" max="4294967295" step="1" hint="Leave blank if unspecified." />
                <div class="sm:col-span-2"><x-admin.textarea name="notes" label="Notes" :value="$vehicle->notes" maxlength="5000" hint="Plain text only; for example, suitability or service conditions." /></div>
            </div>
        </x-admin.card>
        <x-admin.card title="Image">
            <div class="space-y-5 p-5 sm:p-6"><x-admin.image-upload :path="$vehicle->image_path" /><x-admin.field name="image_alt" label="Image alternative text" :value="$vehicle->image_alt" maxlength="255" /></div>
        </x-admin.card>
        <x-admin.card title="Visibility and order">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6"><x-admin.checkbox name="is_published" label="Published" :checked="$vehicle->is_published" hint="Leave unchecked to save as a draft." /><x-admin.field name="sort_order" label="Display order" type="number" :value="$vehicle->sort_order ?? 0" min="0" max="4294967295" step="1" required hint="Lower numbers appear first." /></div>
        </x-admin.card>
        <div class="flex items-center justify-between gap-4"><a href="{{ $returnTo }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300">Cancel</a><x-admin.button :disabled="$types->isEmpty() || $capacities->isEmpty()">Save vehicle</x-admin.button></div>
    </form>
</x-admin.layout>
