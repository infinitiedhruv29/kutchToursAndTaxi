<x-admin.brand :settings="$businessSettings" :dark="true" class="px-3" />
<p class="mt-10 px-3 text-xs font-medium uppercase tracking-widest text-slate-500 dark:text-slate-400">Workspace</p>
<nav aria-label="Admin navigation" class="mt-4 space-y-1.5">
    <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.routes.index')" :active="request()->routeIs('admin.routes.*')" icon="route">Taxi routes</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.custom-packages.index')" :active="request()->routeIs('admin.custom-packages.*')" icon="inbox">Package builder</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.invoices.index')" :active="request()->routeIs('admin.invoices.*')" icon="grid">Invoices & billing</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.destinations.index')" :active="request()->routeIs('admin.destinations.*')">Destinations</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.faqs.index')" :active="request()->routeIs('admin.faqs.*')">General FAQs</x-admin.nav-link>
    <div role="group" aria-label="Gallery" class="space-y-1.5 border-t border-slate-800 pt-3">
        <p class="px-3 text-xs font-medium uppercase tracking-widest text-slate-500">Gallery</p>
        <x-admin.nav-link :href="route('admin.gallery-images.index')" :active="request()->routeIs('admin.gallery-images.*')" icon="grid">Photos</x-admin.nav-link>
        <x-admin.nav-link :href="route('admin.gallery-categories.index')" :active="request()->routeIs('admin.gallery-categories.*')">Photo categories</x-admin.nav-link>
    </div>
    <div role="group" aria-label="Vehicles" class="space-y-1.5 border-y border-slate-800 py-3">
        <p class="px-3 text-xs font-medium uppercase tracking-widest text-slate-500">Vehicles</p>
        <x-admin.nav-link :href="route('admin.vehicles.index')" :active="request()->routeIs('admin.vehicles.*')" icon="route">Vehicles</x-admin.nav-link>
        <x-admin.nav-link :href="route('admin.vehicle-types.index')" :active="request()->routeIs('admin.vehicle-types.*')">Vehicle types</x-admin.nav-link>
        <x-admin.nav-link :href="route('admin.vehicle-capacities.index')" :active="request()->routeIs('admin.vehicle-capacities.*')">Passenger capacities</x-admin.nav-link>
    </div>
    <x-admin.nav-link :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.*')" icon="user">My profile</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')" icon="settings">Business settings</x-admin.nav-link>
</nav>
<div class="mt-auto border-t border-slate-800 px-3 pt-5">
    <p class="text-xs text-slate-500 dark:text-slate-400">Business workspace</p>
    <p class="mt-1 text-xs leading-5 text-slate-400">Your tours, routes, and customer inquiries in one place.</p>
</div>
