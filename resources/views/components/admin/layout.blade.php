@props(['title', 'subtitle' => null])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} · {{ $businessSettings->business_name ?? 'Tours & Taxi' }} · Admin</title>
    @include('admin.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <a href="#main-content"
        class="sr-only rounded-lg bg-white px-4 py-3 text-brand-800 focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50">Skip
        to content</a>
    <aside class="fixed inset-y-0 left-0 hidden w-64 flex-col bg-slate-900 px-5 py-7 lg:flex">
        @include('admin.partials.navigation')</aside>
    <dialog id="admin-drawer" aria-labelledby="drawer-title"
        class="fixed inset-y-0 left-0 m-0 h-dvh max-h-dvh w-72 max-w-full bg-slate-900 p-5 text-white backdrop:bg-slate-950/60 lg:hidden">
        <div class="mb-6 flex items-center justify-between">
            <h2 id="drawer-title" class="text-sm font-semibold">Navigation</h2>
            <button type="button" data-drawer-close aria-label="Close navigation"
                class="rounded-lg p-2 text-slate-400 hover:text-white focus-visible:outline-2 focus-visible:outline-brand-500"><x-admin.icon
                    name="close" /></button>
        </div>
        <div class="flex min-h-[calc(100%_-_4rem)] flex-col">@include('admin.partials.navigation')</div>
    </dialog>
    <div class="min-w-0 lg:pl-64">
        <header class="flex h-20 items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 sm:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" data-drawer-open aria-controls="admin-drawer" aria-expanded="false"
                    aria-label="Open navigation"
                    class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-brand-600 lg:hidden"><x-admin.icon
                        name="menu" /></button>
                <span class="truncate text-sm font-medium text-slate-500">Workspace <span
                        class="mx-2 text-slate-300">/</span> <span
                        class="text-slate-900">{{ $title }}</span></span>
            </div>
            <div class="relative shrink-0" data-profile-menu>
                <button type="button" data-profile-toggle aria-controls="profile-menu" aria-expanded="false"
                    class="flex items-center gap-3 rounded-xl p-1.5 text-left hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600">
                    <x-admin.avatar :user="auth()->user()" />
                    <span class="hidden max-w-40 sm:block"><span
                            class="block truncate text-sm font-semibold">{{ auth()->user()->name }}</span><span
                            class="block text-xs text-slate-500">Administrator</span></span>
                    <span class="sr-only">Account options</span><x-admin.icon name="chevron"
                        class="size-4 text-slate-400" />
                </button>
                <div id="profile-menu"
                    class="absolute right-0 z-30 mt-2 hidden w-56 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">
                    <a href="{{ route('admin.profile.edit') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600"><x-admin.icon
                            name="user" />My profile</a>
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf
                        <button type="submit"
                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600"><x-admin.icon
                                name="logout" />Sign out</button>
                    </form>
                </div>
            </div>
        </header>
        <main id="main-content" tabindex="-1" class="mx-auto px-2 py-2">
            <div class="mb-8">
                <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>
            @if (session('status'))
                <x-admin.alert class="mb-6">{{ session('status') }}</x-admin.alert>
            @endif
            {{ $slot }}
        </main>
        <footer class="px-4 pb-6 text-xs text-slate-400 sm:px-8">
            {{ $businessSettings->business_name ?? 'Tours & Taxi' }} · Administration</footer>
    </div>
</body>

</html>
