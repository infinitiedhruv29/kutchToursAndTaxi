<x-admin.brand :settings="$businessSettings" :dark="true" class="px-3" />
<p class="mt-10 px-3 text-xs font-medium uppercase tracking-widest text-slate-500">Workspace</p>
<nav aria-label="Admin navigation" class="mt-4 space-y-1.5">
    <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.*')" icon="user">My profile</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')" icon="settings">Business settings</x-admin.nav-link>
</nav>
<div class="mt-auto border-t border-slate-800 px-3 pt-5">
    <p class="text-xs text-slate-500">Business workspace</p>
    <p class="mt-1 text-xs leading-5 text-slate-400">Your tours, routes, and customer inquiries in one place.</p>
</div>
