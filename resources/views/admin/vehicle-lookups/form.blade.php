<x-admin.layout :title="($record->exists ? 'Edit ' : 'Create ').strtolower($module['singular'])" subtitle="Keep your vehicle choices clear and up to date.">
    <form method="POST" action="{{ $record->exists ? route($module['prefix'].'.update', $record) : route($module['prefix'].'.store') }}" data-admin-form data-loading-text="Saving…" class="max-w-3xl space-y-6">
        @csrf
        @if ($record->exists) @method('PUT') @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        <x-admin.card class="space-y-5 p-5 sm:p-6">
            <x-admin.field :name="$module['field']" :label="$module['capacity'] ? 'Display label' : 'Name'" :value="$record->getAttribute($module['field'])" maxlength="255" required />
            @if ($module['capacity'])
                <x-admin.field name="passenger_capacity" label="Number of passengers" type="number" :value="$record->passenger_capacity" min="1" max="65535" step="1" required hint="Exclude the driver. Different display labels may use the same passenger count." />
            @endif
            <x-admin.field name="sort_order" label="Display order" type="number" :value="$record->sort_order ?? 0" min="0" max="4294967295" step="1" required hint="Lower numbers appear first." />
        </x-admin.card>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ $returnTo }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300">Cancel</a>
            <x-admin.button>Save {{ strtolower($module['singular']) }}</x-admin.button>
        </div>
    </form>
</x-admin.layout>
