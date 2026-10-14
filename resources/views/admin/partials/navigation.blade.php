<div class="flex items-center gap-3 px-3">
    <span class="flex size-10 items-center justify-center rounded-xl bg-brand-600 text-white"><x-admin.icon name="route" /></span>
    <div><p class="text-sm font-semibold text-white">Tours & Taxi</p><p class="mt-0.5 text-xs text-slate-400">Administration</p></div>
</div>
<p class="mt-10 px-3 text-xs font-medium uppercase tracking-widest text-slate-500">Workspace</p>
<nav aria-label="Admin navigation" class="mt-4 space-y-1.5">
    <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
    <x-admin.nav-link :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.*')" icon="user">My profile</x-admin.nav-link>
</nav>
<div class="mt-auto border-t border-slate-800 px-3 pt-5">
    <p class="text-xs text-slate-500">Business workspace</p>
    <p class="mt-1 text-xs leading-5 text-slate-400">Your tours, routes, and customer inquiries in one place.</p>
</div>
