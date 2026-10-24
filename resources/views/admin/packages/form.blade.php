<x-admin.layout
    :title="$package->exists ? 'Edit Tour Package' : 'Create Tour Package'"
    subtitle="Design holiday package itineraries, configure pricing tiers, and publish to the customer portal."
>
    <form
        method="POST"
        action="{{ $package->exists ? route('admin.packages.update', $package) : route('admin.packages.store') }}"
        enctype="multipart/form-data"
        data-admin-form
        data-loading-text="Saving tour package…"
        class="space-y-6 max-w-5xl"
    >
        @csrf
        @if ($package->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">

        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary>
                {{ $errors->first() }}
            </x-admin.alert>
        @endif

        {{-- Basic Information --}}
        <x-admin.card title="Package Overview">
            <div class="space-y-5 p-5 sm:p-6">
                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <x-admin.field
                            name="title"
                            label="Package Title"
                            :value="old('title', $package->title)"
                            placeholder="e.g. White Rann & UNESCO Dholavira"
                            required
                            maxlength="255"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Category / Type <span class="text-red-500">*</span>
                        </label>
                        <select name="package_type" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <option value="Kutch" @selected(old('package_type', $package->package_type) === 'Kutch')>Kutch Packages</option>
                            <option value="Outstation" @selected(old('package_type', $package->package_type) === 'Outstation')>Outstation Tours</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field
                        name="slug"
                        label="URL Slug"
                        :value="old('slug', $package->slug)"
                        placeholder="e.g. white-rann-dholavira"
                        hint="Public URL will be /package-{slug}."
                        required
                        maxlength="255"
                    />
                    <div class="flex items-center pt-6">
                        <x-admin.checkbox
                            name="auto_slug"
                            label="Auto-generate slug from title"
                            :checked="!$package->exists || old('auto_slug')"
                        />
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field
                        name="duration"
                        label="Duration"
                        :value="old('duration', $package->duration)"
                        placeholder="e.g. 3 Nights - 4 Days"
                        maxlength="255"
                    />
                    <x-admin.field
                        name="highlight"
                        label="Special Highlight Badge"
                        :value="old('highlight', $package->highlight)"
                        placeholder="e.g. Full Moon Desert & UNESCO Ruins"
                        maxlength="255"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                        Package Description & Highlights
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Provide an engaging overview of the holiday package..."
                        class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 focus:border-brand-600 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >{{ old('description', $package->description) }}</textarea>
                </div>
            </div>
        </x-admin.card>

        {{-- Cover Image --}}
        <x-admin.card title="Cover Photograph">
            <div class="space-y-5 p-5 sm:p-6">
                <x-admin.image-upload
                    :path="$package->image_path"
                    label="Package cover photo (JPG, PNG, WebP up to 5MB)"
                    preview-alt="Package preview"
                />
                <x-admin.field
                    name="image_alt"
                    label="Image alternative text (SEO)"
                    :value="old('image_alt', $package->image_alt)"
                    placeholder="e.g. Camel safari on White Rann of Kutch"
                    maxlength="255"
                />
            </div>
        </x-admin.card>

        {{-- Pricing Tiers --}}
        <x-admin.card title="Tiered Pricing (INR)">
            <div class="grid gap-5 p-5 sm:grid-cols-3 sm:p-6">
                <x-admin.field
                    name="price_2p"
                    label="2 Persons (Total Price)"
                    type="number"
                    step="0.01"
                    min="0"
                    :value="old('price_2p', $package->price_2p)"
                    placeholder="e.g. 28000"
                    hint="Base rate displayed on website."
                />
                <x-admin.field
                    name="price_4p"
                    label="4 Persons (Total Price)"
                    type="number"
                    step="0.01"
                    min="0"
                    :value="old('price_4p', $package->price_4p)"
                    placeholder="e.g. 40000"
                    hint="Group tier pricing."
                />
                <x-admin.field
                    name="price_6p"
                    label="6 Persons (Total Price)"
                    type="number"
                    step="0.01"
                    min="0"
                    :value="old('price_6p', $package->price_6p)"
                    placeholder="e.g. 52000"
                    hint="Large family tier pricing."
                />
            </div>
        </x-admin.card>

        {{-- Day-by-Day Itineraries --}}
        <x-admin.card title="Day-by-Day Tour Itinerary">
            <div class="space-y-6 p-5 sm:p-6">
                <p class="text-xs text-slate-500">
                    Add daily stops and activity schedules. Spots will be displayed as badge pills on the package detail page.
                </p>

                <div id="itinerary-days-container" class="space-y-4">
                    @php
                        $days = [];
                        if (old('day_title')) {
                            $oldTitles = old('day_title', []);
                            $oldSpots = old('day_spots', []);
                            $oldDescs = old('day_description', []);
                            $oldNotes = old('day_note', []);
                            for ($i = 0; $i < count($oldTitles); $i++) {
                                $days[] = [
                                    'title' => $oldTitles[$i],
                                    'spots' => isset($oldSpots[$i]) ? (is_array($oldSpots[$i]) ? $oldSpots[$i] : explode("\n", $oldSpots[$i])) : [],
                                    'description' => $oldDescs[$i] ?? '',
                                    'note' => $oldNotes[$i] ?? '',
                                ];
                            }
                        } elseif ($package->exists && $package->itineraries->isNotEmpty()) {
                            foreach ($package->itineraries as $it) {
                                $days[] = [
                                    'title' => $it->title,
                                    'spots' => $it->spots ?? [],
                                    'description' => $it->description,
                                    'note' => $it->note,
                                ];
                            }
                        }

                        if (empty($days)) {
                            $days = [
                                [
                                    'title' => 'Arrival & Bhuj Local Sightseeing',
                                    'spots' => ['Aina Mahal', 'Prag Mahal', 'Kutch Museum'],
                                    'description' => 'Pickup from Bhuj Airport / Railway Station and transfer to hotel.',
                                    'note' => 'Overnight stay in Bhuj',
                                ],
                            ];
                        }
                    @endphp

                    @foreach ($days as $index => $day)
                        <div class="itinerary-day-card rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/40">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-4">
                                <span class="day-badge inline-flex items-center rounded-lg bg-brand-700 px-3 py-1 text-xs font-bold text-white">
                                    Day {{ $index + 1 }}
                                </span>
                                <button type="button" onclick="removeItineraryDay(this)" class="text-xs font-medium text-red-600 hover:text-red-700 dark:text-red-400">
                                    ✕ Remove Day
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                        Day Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="day_title[]" value="{{ $day['title'] ?? '' }}" required placeholder="e.g. Bhuj to Dhordo (White Desert Sunset)" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                        Sightseeing Spots Visited (one per line)
                                    </label>
                                    <textarea name="day_spots[]" rows="2" placeholder="White Rann&#10;Kalo Dungar&#10;Magnetic Hill" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ is_array($day['spots'] ?? null) ? implode("\n", $day['spots']) : ($day['spots'] ?? '') }}</textarea>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                            Schedule & Activities
                                        </label>
                                        <textarea name="day_description[]" rows="2" placeholder="Detailed activities for this day..." class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $day['description'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                            Night Stay / Meal Note
                                        </label>
                                        <input type="text" name="day_note[]" value="{{ $day['note'] ?? '' }}" placeholder="e.g. Night stay in Tent City / Dhordo resort" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addItineraryDay()" class="inline-flex items-center gap-2 rounded-xl border border-dashed border-slate-300 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:border-brand-600 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300">
                    + Add Another Day
                </button>
            </div>
        </x-admin.card>

        {{-- Inclusions & Exclusions --}}
        <x-admin.card title="Inclusions & Exclusions">
            <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 mb-2">
                        What's Included (one per line)
                    </label>
                    @php
                        $incLines = $package->exists ? $package->inclusions->pluck('content')->implode("\n") : '';
                        if (empty($incLines)) {
                            $incLines = "All transfers and sightseeing by private AC Cab\nExperienced local driver with all road permits\nPickup and drop from Bhuj Railway Station / Airport\nToll taxes, state road taxes, parking charges and driver allowance";
                        }
                        $incText = old('inclusions_text', $incLines);
                    @endphp
                    <textarea name="inclusions_text" rows="5" class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $incText }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-red-700 dark:text-red-400 mb-2">
                        What's Excluded (one per line)
                    </label>
                    @php
                        $excLines = $package->exists ? $package->exclusions->pluck('content')->implode("\n") : '';
                        if (empty($excLines)) {
                            $excLines = "Hotel accommodation and meals\nWhite Rann entry permit fees\nMonuments and palace entry tickets or guide charges\nPersonal expenses, laundry, tips and camera fees";
                        }
                        $excText = old('exclusions_text', $excLines);
                    @endphp
                    <textarea name="exclusions_text" rows="5" class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $excText }}</textarea>
                </div>
            </div>
        </x-admin.card>

        {{-- Publication, Order & SEO --}}
        <x-admin.card title="Publication & SEO Settings">
            <div class="space-y-5 p-5 sm:p-6">
                <div class="grid gap-5 sm:grid-cols-3">
                    <x-admin.checkbox
                        name="is_published"
                        label="Published"
                        :checked="old('is_published', $package->is_published ?? true)"
                        hint="Check to show on the public website."
                    />
                    <x-admin.checkbox
                        name="is_featured"
                        label="Featured on Home Page"
                        :checked="old('is_featured', $package->is_featured ?? false)"
                        hint="Check to display in home page tour grid."
                    />
                    <x-admin.field
                        name="sort_order"
                        label="Display order"
                        type="number"
                        :value="old('sort_order', $package->sort_order ?? 0)"
                        hint="Lower numbers appear first."
                        required
                        min="0"
                    />
                </div>

                <div class="border-t border-slate-200 dark:border-slate-800 pt-5 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">SEO Meta Tags (Optional)</h4>
                    <x-admin.field
                        name="seo_title"
                        label="Custom SEO Meta Title"
                        :value="old('seo_title', $package->seo_title)"
                        placeholder="Leave blank to use package title"
                        maxlength="255"
                    />
                    <x-admin.textarea
                        name="seo_description"
                        label="Custom SEO Meta Description"
                        :value="old('seo_description', $package->seo_description)"
                        placeholder="Compelling description for Google search snippets (150-160 characters)"
                        maxlength="2000"
                    />
                </div>
            </div>
        </x-admin.card>

        <div class="flex items-center justify-between gap-4 pt-4">
            <a href="{{ $returnTo }}" class="rounded-lg px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                Cancel
            </a>
            <button
                type="submit"
                class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600"
            >
                {{ $package->exists ? 'Update Tour Package' : 'Create Tour Package' }}
            </button>
        </div>
    </form>

    <script>
        function updateDayNumbers() {
            const cards = document.querySelectorAll('.itinerary-day-card');
            cards.forEach((card, idx) => {
                const badge = card.querySelector('.day-badge');
                if (badge) {
                    badge.textContent = `Day ${idx + 1}`;
                }
            });
        }

        function removeItineraryDay(btn) {
            const container = document.getElementById('itinerary-days-container');
            if (container.querySelectorAll('.itinerary-day-card').length <= 1) {
                alert('At least one itinerary day is required.');
                return;
            }
            btn.closest('.itinerary-day-card').remove();
            updateDayNumbers();
        }

        function addItineraryDay() {
            const container = document.getElementById('itinerary-days-container');
            const dayNum = container.querySelectorAll('.itinerary-day-card').length + 1;

            const card = document.createElement('div');
            card.className = 'itinerary-day-card rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/40';
            card.innerHTML = `
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-4">
                    <span class="day-badge inline-flex items-center rounded-lg bg-brand-700 px-3 py-1 text-xs font-bold text-white">
                        Day ${dayNum}
                    </span>
                    <button type="button" onclick="removeItineraryDay(this)" class="text-xs font-medium text-red-600 hover:text-red-700 dark:text-red-400">
                        ✕ Remove Day
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                            Day Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="day_title[]" required placeholder="e.g. Exploration & Local Viewpoints" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                            Sightseeing Spots Visited (one per line)
                        </label>
                        <textarea name="day_spots[]" rows="2" placeholder="Spot name 1&#10;Spot name 2" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"></textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                Schedule & Activities
                            </label>
                            <textarea name="day_description[]" rows="2" placeholder="Detailed activities for this day..." class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                Night Stay / Meal Note
                            </label>
                            <input type="text" name="day_note[]" placeholder="e.g. Night stay in Bhuj hotel" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        }
    </script>
</x-admin.layout>
