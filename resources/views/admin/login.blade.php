<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · {{ $businessSettings->business_name ?? 'Tours & Taxi' }} · Admin</title>
    @include('admin.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="relative hidden flex-col justify-between overflow-hidden bg-slate-900 p-12 text-white lg:flex">
            <x-admin.brand :settings="$businessSettings" :dark="true" />
            <div class="max-w-lg py-16">
                <p class="mb-6 text-xs font-semibold uppercase tracking-[0.25em] text-brand-200">Your business workspace
                </p>
                <h1 class="text-5xl font-semibold leading-tight tracking-tight">Every journey<br>starts here.</h1>
                <p class="mt-6 max-w-sm text-base leading-7 text-slate-400">A clear view of your business. One place for
                    your routes, fleet, and customer inquiries.</p>
            </div>
            <p class="text-xs text-slate-500">Built for the way you work.</p>
        </section>
        <section class="flex items-center justify-center px-5 py-12 sm:px-12">
            <div class="w-full max-w-sm">
                <x-admin.brand :settings="$businessSettings" class="mb-8" />
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-brand-700">Administration</p>
                <h2 class="text-3xl font-semibold tracking-tight">Welcome back</h2>
                <p class="mt-3 text-sm leading-6 text-slate-500">Sign in to your business workspace.</p>
                @if (session('status'))
                    <x-admin.alert class="mt-6">{{ session('status') }}</x-admin.alert>
                @endif
                <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
                    @csrf
                    <x-admin.field name="email" label="Email address" type="email" autocomplete="username" required
                        autofocus />
                    <x-admin.field name="password" label="Password" type="password" autocomplete="current-password"
                        required />
                    <label class="flex items-center gap-2.5 text-sm text-slate-600"><input type="checkbox"
                            name="remember" value="1" @checked(old('remember'))
                            class="size-4 accent-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">Remember
                        me</label>
                    <x-admin.button class="w-full">Sign in <x-admin.icon name="arrow"
                            class="size-4" /></x-admin.button>
                </form>
                <p class="mt-8 text-xs leading-5 text-slate-400">Need access or help with a forgotten password? Contact
                    your website administrator.</p>
            </div>
        </section>
    </main>
</body>

</html>
