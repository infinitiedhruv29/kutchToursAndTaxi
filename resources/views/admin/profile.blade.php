<x-admin.layout title="My profile" subtitle="Manage your account details and keep your access secure.">
    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.card class="h-fit p-6">
            <x-admin.avatar :user="$user" class="size-20 text-2xl" />
            <h2 class="mt-5 break-words text-lg font-semibold">{{ $user->name }}</h2>
            <p class="mt-1 break-all text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
            <span class="mt-4 inline-flex rounded-full bg-brand-50 dark:bg-brand-500/10 px-3 py-1 text-xs font-medium text-brand-800 dark:text-brand-200">Administrator</span>
            <p class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-5 text-xs text-slate-400">Account created {{ $user->created_at->format('d M Y') }}</p>
        </x-admin.card>
        <div class="space-y-6 xl:col-span-2">
            <x-admin.card title="Personal information" description="Update your name, email address, and profile photo.">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" data-admin-form data-loading-text="Saving profile…" class="space-y-5 p-5 sm:p-6">
                    @csrf @method('PATCH')
                    <x-admin.field name="name" label="Full name" :value="$user->name" autocomplete="name" required />
                    <x-admin.field name="email" label="Email address" type="email" :value="$user->email" autocomplete="email" required />
                    <x-admin.field name="current_password" id="profile-current-password" label="Current password" type="password" autocomplete="current-password" hint="Required only when changing your email address." />
                    <x-admin.field name="avatar" label="Profile photo" type="file" accept="image/jpeg,image/png,image/webp" hint="JPEG, PNG, or WebP. Maximum size: 2 MB." />
                    @if ($user->avatar_path)<label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300"><input type="checkbox" name="remove_avatar" value="1" @checked(old('remove_avatar')) class="size-4 accent-brand-700">Remove current photo</label>@endif
                    <x-admin.button>Save changes</x-admin.button>
                </form>
            </x-admin.card>
            <x-admin.card title="Change password" description="Choose a password of at least 12 characters containing letters and numbers.">
                <form method="POST" action="{{ route('admin.profile.password') }}" data-admin-form data-loading-text="Updating password…" class="space-y-5 p-5 sm:p-6">
                    @csrf @method('PUT')
                    <x-admin.field name="current_password" id="password-current-password" bag="password" label="Current password" type="password" autocomplete="current-password" required />
                    <x-admin.field name="password" bag="password" label="New password" type="password" autocomplete="new-password" minlength="12" required />
                    <x-admin.field name="password_confirmation" bag="password" label="Confirm new password" type="password" autocomplete="new-password" minlength="12" required />
                    <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">Changing your password signs out other sessions. You will stay signed in on this device.</p>
                    <x-admin.button>Update password</x-admin.button>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-admin.layout>
