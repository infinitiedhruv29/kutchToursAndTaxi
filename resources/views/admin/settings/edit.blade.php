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

        <x-admin.card title="Website theme & appearance"
            description="Choose the primary brand color used for buttons, badges, banners, and highlights across the public website.">
            <div class="space-y-6 p-5 sm:p-6">
                <div>
                    <label for="primary_color" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                        Primary website color
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="relative flex items-center">
                            <input type="color" id="primary_color_picker" 
                                value="{{ old('primary_color', $settings->primary_color ?? '#E7BE67') }}" 
                                class="h-11 w-14 cursor-pointer rounded-sm border border-slate-200 bg-white p-1 dark:border-slate-700 dark:bg-slate-900"
                                oninput="document.getElementById('primary_color').value = this.value; document.getElementById('primary_color_preview').style.backgroundColor = this.value;">
                        </div>
                        <div class="flex-1 min-w-[180px] max-w-xs">
                            <input type="text" id="primary_color" name="primary_color" 
                                value="{{ old('primary_color', $settings->primary_color ?? '#E7BE67') }}" 
                                maxlength="7" placeholder="#E7BE67"
                                class="block min-h-11 w-full rounded-sm border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono uppercase text-slate-900 placeholder:text-slate-400 focus:border-brand-600 focus:outline-2 focus:outline-brand-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)){ document.getElementById('primary_color_picker').value = this.value; document.getElementById('primary_color_preview').style.backgroundColor = this.value; }">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Preview:</span>
                            <span id="primary_color_preview" class="inline-block h-8 w-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-105" 
                                style="background-color: {{ old('primary_color', $settings->primary_color ?? '#E7BE67') }}"></span>
                        </div>
                    </div>
                    @error('primary_color')
                        <p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Quick Preset Swatches
                    </label>
                    <div class="flex flex-wrap gap-2.5" id="color-presets">
                        @php
                            $presets = [
                                ['label' => 'Kutch Desert Gold (Brand Logo)', 'color' => '#E7BE67'],
                                ['label' => 'Terracotta Maroon (Default)', 'color' => '#9E2A2B'],
                                ['label' => 'Desert Ochre', 'color' => '#DCA134'],
                                ['label' => 'Ajrakh Indigo', 'color' => '#1A355E'],
                                ['label' => 'Kutch Emerald', 'color' => '#0F766E'],
                                ['label' => 'Sunset Crimson', 'color' => '#C2410C'],
                                ['label' => 'Ocean Blue', 'color' => '#0284C7'],
                            ];
                        @endphp
                        @foreach ($presets as $preset)
                            <button type="button" 
                                onclick="selectColorSwatch('{{ $preset['color'] }}')"
                                title="{{ $preset['label'] }}: {{ $preset['color'] }}"
                                class="group flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:shadow dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                <span class="h-4 w-4 rounded-full shadow-inner" style="background-color: {{ $preset['color'] }}"></span>
                                <span>{{ $preset['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
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
            <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">Changes apply immediately across the website and documents after saving.</p>
            <x-admin.button class="w-full shrink-0 sm:w-auto">Save business settings</x-admin.button>
        </div>
    </form>

    <script>
        function selectColorSwatch(hex) {
            const input = document.getElementById('primary_color');
            const picker = document.getElementById('primary_color_picker');
            const preview = document.getElementById('primary_color_preview');
            if (input) input.value = hex;
            if (picker) picker.value = hex;
            if (preview) preview.style.backgroundColor = hex;
        }
    </script>
</x-admin.layout>
