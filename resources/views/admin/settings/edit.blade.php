<x-admin.layout title="Business settings" subtitle="Manage the business details used across your website and documents.">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" data-admin-form data-loading-text="Saving settings…" class="space-y-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary>
                <p class="font-semibold">Please check the highlighted fields.</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-admin.alert>
        @endif

        <x-admin.card title="Business identity"
            description="All details are optional. Leave information empty until it is available.">
            <div class="space-y-6 p-5 sm:p-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <x-admin.field name="business_name" label="Business name" :value="$settings->business_name" maxlength="255"
                        autocomplete="organization" />
                    <x-admin.field name="gst_number" label="GST number" :value="$settings->gst_number" maxlength="30" />
                </div>
                <div class="grid gap-6 lg:grid-cols-3">
                    <x-admin.branding-upload name="logo" label="Logo for light backgrounds" :path="$settings->logo_path"
                        hint="JPEG, PNG, or WebP. Maximum 2 MB. A transparent PNG or WebP works well." />
                    <x-admin.branding-upload name="dark_logo" label="Logo for dark backgrounds" :path="$settings->dark_logo_path"
                        :dark="true"
                        hint="JPEG, PNG, or WebP. Maximum 2 MB. Use a light-colored logo for the slate sidebar." />
                    <x-admin.branding-upload name="favicon" label="Favicon" :path="$settings->favicon_path" accept="image/png"
                        hint="PNG only, maximum 512 KB. A square image is recommended." />
                </div>
                <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">New uploads replace the current images when you save. A
                    replacement upload takes priority over its removal checkbox.</p>
            </div>
        </x-admin.card>

        <x-admin.card title="Contact information"
            description="These contact methods will be available to the public website when configured.">
            <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-2">
                <x-admin.field name="contact_number" label="Primary phone number" type="tel" :value="$settings->contact_number"
                    maxlength="30" autocomplete="tel" />
                <x-admin.field name="contact_number_2" label="Secondary phone number" type="tel" :value="$settings->contact_number_2"
                    maxlength="30" />
                <x-admin.field name="whatsapp_number" label="WhatsApp number" type="tel" :value="$settings->whatsapp_number"
                    maxlength="30"
                    hint="Include the country code, e.g. +91 98765 43210. Do not enter a WhatsApp link." />
                <x-admin.field name="contact_email" label="Business email" type="email" :value="$settings->contact_email"
                    maxlength="255" autocomplete="email" />
                <div class="md:col-span-2"><x-admin.textarea name="address" label="Business address" :value="$settings->address"
                        maxlength="5000" autocomplete="street-address" /></div>
                <div class="md:col-span-2"><x-admin.field name="google_map_link" label="Google Maps link" type="url"
                        :value="$settings->google_map_link" maxlength="2048"
                        hint="Paste the HTTP or HTTPS share link for your location, rather than iframe or embed code." />
                </div>
            </div>
        </x-admin.card>

        <x-admin.card title="Social links" description="Leave a link empty to keep that social profile hidden.">
            <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-3">
                <x-admin.field name="instagram_link" label="Instagram" type="url" :value="$settings->instagram_link"
                    maxlength="2048" />
                <x-admin.field name="facebook_link" label="Facebook" type="url" :value="$settings->facebook_link"
                    maxlength="2048" />
                <x-admin.field name="youtube_link" label="YouTube" type="url" :value="$settings->youtube_link" maxlength="2048" />
            </div>
        </x-admin.card>

        <x-admin.card title="Package defaults"
            description="General inclusions and exclusions for future package pages and documents.">
            <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-2">
                <x-admin.textarea name="general_inclusions" label="General inclusions" :value="$settings->general_inclusions"
                    :rows="8"
                    hint="One item per line. Up to 100 items, with a maximum of 500 characters each." />
                <x-admin.textarea name="general_exclusions" label="General exclusions" :value="$settings->general_exclusions"
                    :rows="8"
                    hint="One item per line. Up to 100 items, with a maximum of 500 characters each." />
            </div>
        </x-admin.card>

        <div class="flex flex-row flex-wrap items-start justify-between gap-4 sm:flex-nowrap sm:items-center">
            <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">Changes apply after saving. Theme colors are fixed and are not
                part of these settings.</p>
            <x-admin.button class="w-full shrink-0 sm:w-auto">Save business settings</x-admin.button>
        </div>
    </form>
</x-admin.layout>
