@php
    $editing = $taxiRoute->exists;
    $duration = $taxiRoute->estimated_duration_minutes;
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
                <x-admin.rich-text name="description" label="Full description" :value="$taxiRoute->description" />
            </div>
        </x-admin.card>

        <x-admin.card title="Route image">
            <div class="grid gap-6 p-5 sm:p-6 md:grid-cols-2">
                <x-admin.branding-upload name="image" label="Route image" :path="$taxiRoute->image_path" hint="JPEG, PNG, or WebP, up to 2 MB. A new upload takes priority over removal." />
                <x-admin.field name="image_alt" label="Image description (alt text)" :value="$taxiRoute->image_alt" maxlength="255" hint="Describe the image for visitors using screen readers." />
            </div>
        </x-admin.card>

        <x-admin.card title="Fares" description="All fares are in INR. Leave an amount empty to use “Price on inquiry”.">
            <div class="space-y-5 p-5 sm:p-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <x-admin.field name="forward_one_way_fare" label="Forward one-way fare (₹)" type="number" :value="$taxiRoute->forward_one_way_fare" min="0" max="9999999999.99" step="0.01" />
                    <x-admin.field name="forward_round_trip_fare" label="Forward round-trip fare (₹)" type="number" :value="$taxiRoute->forward_round_trip_fare" min="0" max="9999999999.99" step="0.01" />
                </div>
                <x-admin.checkbox name="reverse_enabled" label="Allow reverse travel" :checked="$taxiRoute->reverse_enabled" hint="Turning this off retains saved reverse fares. Blank reverse fares inherit the matching forward fare." />
                <fieldset data-reverse-fares class="grid gap-5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 p-4 md:grid-cols-2">
                    <legend class="px-2 text-sm font-medium text-slate-700 dark:text-slate-200">Reverse fares</legend>
                    <div><x-admin.field name="reverse_one_way_fare" label="Reverse one-way override (₹)" type="number" :value="$taxiRoute->reverse_one_way_fare" min="0" max="9999999999.99" step="0.01" /><p data-reverse-summary="one_way" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Blank inherits the forward one-way fare.</p></div>
                    <div><x-admin.field name="reverse_round_trip_fare" label="Reverse round-trip override (₹)" type="number" :value="$taxiRoute->reverse_round_trip_fare" min="0" max="9999999999.99" step="0.01" /><p data-reverse-summary="round_trip" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Blank inherits the forward round-trip fare.</p></div>
                </fieldset>
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
