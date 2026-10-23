<x-admin.layout
    :title="$customPackage->exists ? 'Edit Custom Proposal' : 'Create Custom Proposal'"
    subtitle="Design a personalized itinerary, configure multi-tier pricing, and export a branded quote."
>
    <form
        method="POST"
        action="{{ $customPackage->exists ? route('admin.custom-packages.update', $customPackage) : route('admin.custom-packages.store') }}"
        data-admin-form
        data-loading-text="Processing proposal…"
        class="space-y-6 max-w-5xl"
    >
        @csrf
        @if ($customPackage->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        <input type="hidden" name="submit_action" id="form-action" value="save">

        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary>
                {{ $errors->first() }}
            </x-admin.alert>
        @endif

        {{-- Client Details Card --}}
        <x-admin.card title="Client Information">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <x-admin.field
                    name="client_name"
                    label="Client name"
                    :value="old('client_name', $customPackage->client_name)"
                    placeholder="e.g. Rajesh Sharma"
                    required
                    maxlength="255"
                />
                <x-admin.field
                    name="client_phone"
                    label="Contact number"
                    :value="old('client_phone', $customPackage->client_phone)"
                    placeholder="e.g. +91 98765 43210"
                    hint="Optional phone number (10 to 20 digits)."
                    maxlength="30"
                />
            </div>
        </x-admin.card>

        {{-- Tour Overview Card --}}
        <x-admin.card title="Tour Overview">
            <div class="space-y-5 p-5 sm:p-6">
                <x-admin.field
                    name="title"
                    label="Proposal / Tour title"
                    :value="old('title', $customPackage->title)"
                    placeholder="e.g. 4 Days Kutch Heritage & White Desert Tour"
                    required
                    maxlength="255"
                />
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field
                        name="duration"
                        label="Duration"
                        :value="old('duration', $customPackage->duration)"
                        placeholder="e.g. 3 Nights · 4 Days"
                        maxlength="255"
                    />
                    <x-admin.field
                        name="highlight"
                        label="Special highlight badge"
                        :value="old('highlight', $customPackage->highlight)"
                        placeholder="e.g. Full Moon White Rann Special"
                        maxlength="255"
                    />
                </div>
            </div>
        </x-admin.card>

        {{-- Pricing Tiers Card --}}
        <x-admin.card title="Estimated Pricing Tiers (INR)">
            <div class="grid gap-5 p-5 sm:grid-cols-3 sm:p-6">
                <x-admin.field
                    name="price_2p"
                    label="2 Persons (Sedan)"
                    type="number"
                    :value="old('price_2p', $customPackage->price_2p)"
                    min="0"
                    step="0.01"
                    placeholder="e.g. 18500"
                />
                <x-admin.field
                    name="price_4p"
                    label="4 Persons (Ertiga)"
                    type="number"
                    :value="old('price_4p', $customPackage->price_4p)"
                    min="0"
                    step="0.01"
                    placeholder="e.g. 24000"
                />
                <x-admin.field
                    name="price_6p"
                    label="6 Persons (Innova)"
                    type="number"
                    :value="old('price_6p', $customPackage->price_6p)"
                    min="0"
                    step="0.01"
                    placeholder="e.g. 32000"
                />
            </div>
        </x-admin.card>

        {{-- Day by Day Itinerary Builder --}}
        <x-admin.card title="Day-by-Day Itinerary">
            <div class="p-5 sm:p-6 space-y-6">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Add day titles, sightseeing spots, and detailed descriptions for each day of the journey.
                </p>

                <div id="itinerary-days-container" class="space-y-4">
                    @php
                        $days = old('day_title') ? [] : ($customPackage->itineraries ?? []);
                        if (old('day_title')) {
                            $oldTitles = old('day_title', []);
                            $oldSpots = old('day_spots', []);
                            $oldDescs = old('day_description', []);
                            $oldNotes = old('day_note', []);
                            for ($i = 0; $i < count($oldTitles); $i++) {
                                $days[] = [
                                    'title' => $oldTitles[$i],
                                    'spots' => isset($oldSpots[$i]) ? explode("\n", $oldSpots[$i]) : [],
                                    'description' => $oldDescs[$i] ?? '',
                                    'note' => $oldNotes[$i] ?? '',
                                ];
                            }
                        }
                        if (empty($days)) {
                            $days = [['title' => 'Arrival & Bhuj Local Sightseeing', 'spots' => ['Aina Mahal', 'Prag Mahal', 'Kutch Museum'], 'description' => 'Pickup from Bhuj Airport/Railway station and check in to hotel.', 'note' => 'Night stay in Bhuj']];
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
                                    <input type="text" name="day_title[]" value="{{ $day['title'] ?? '' }}" required placeholder="e.g. Bhuj to Dhordo (White Desert)" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                        Sightseeing Spots (one per line)
                                    </label>
                                    <textarea name="day_spots[]" rows="2" placeholder="White Rann&#10;Kalo Dungar&#10;Gandhi Nu Gam" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ is_array($day['spots'] ?? null) ? implode("\n", $day['spots']) : ($day['spots'] ?? '') }}</textarea>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                            Description
                                        </label>
                                        <textarea name="day_description[]" rows="2" placeholder="Brief overview of day's schedule..." class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $day['description'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                            Night Stay / Travel Note
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

        {{-- Inclusions & Exclusions Card --}}
        <x-admin.card title="Terms & Details">
            <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 mb-2">
                        What's Included (one per line)
                    </label>
                    @php
                        $incText = old('inclusions_text', $customPackage->inclusions ? implode("\n", $customPackage->inclusions) : "AC Private Vehicle for all sightseeing & transfers\nDriver allowances, toll tax, parking & state permits\nPickup and drop from Bhuj Airport/Railway Station\nAll toll taxes and parking charges");
                    @endphp
                    <textarea name="inclusions_text" rows="5" class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $incText }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-red-700 dark:text-red-400 mb-2">
                        What's Excluded (one per line)
                    </label>
                    @php
                        $excText = old('exclusions_text', $customPackage->exclusions ? implode("\n", $customPackage->exclusions) : "Hotel accommodation and meals\nWhite Rann entry permit fees\nMonuments/Palace entry tickets & guide fees\nPersonal expenses, laundry, and tips");
                    @endphp
                    <textarea name="exclusions_text" rows="5" class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $excText }}</textarea>
                </div>
            </div>
        </x-admin.card>

        <div class="flex flex-wrap items-center justify-between gap-4 pt-4">
            <a href="{{ $returnTo }}" class="rounded-lg px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300 dark:hover:bg-slate-800">
                Cancel
            </a>
            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    onclick="document.getElementById('form-action').value='save';"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    Save Proposal
                </button>
                <button
                    type="submit"
                    onclick="document.getElementById('form-action').value='export';"
                    class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600"
                >
                    Save & Download PDF
                </button>
            </div>
        </div>
    </form>

    <script>
        function addItineraryDay() {
            const container = document.getElementById('itinerary-days-container');
            const dayCount = container.querySelectorAll('.itinerary-day-card').length + 1;
            const card = document.createElement('div');
            card.className = 'itinerary-day-card rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/40';
            card.innerHTML = `
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-4">
                    <span class="day-badge inline-flex items-center rounded-lg bg-brand-700 px-3 py-1 text-xs font-bold text-white">
                        Day ${dayCount}
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
                        <input type="text" name="day_title[]" required placeholder="e.g. Day ${dayCount} Itinerary" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                            Sightseeing Spots (one per line)
                        </label>
                        <textarea name="day_spots[]" rows="2" placeholder="Spot 1&#10;Spot 2" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"></textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                Description
                            </label>
                            <textarea name="day_description[]" rows="2" placeholder="Day activities..." class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">
                                Night Stay / Travel Note
                            </label>
                            <input type="text" name="day_note[]" placeholder="Night stay location..." class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
            renumberDays();
        }

        function removeItineraryDay(btn) {
            const container = document.getElementById('itinerary-days-container');
            if (container.querySelectorAll('.itinerary-day-card').length <= 1) {
                alert('At least one day is required in the itinerary.');
                return;
            }
            btn.closest('.itinerary-day-card').remove();
            renumberDays();
        }

        function renumberDays() {
            const container = document.getElementById('itinerary-days-container');
            const badges = container.querySelectorAll('.day-badge');
            badges.forEach((badge, idx) => {
                badge.textContent = `Day ${idx + 1}`;
            });
        }
    </script>
</x-admin.layout>
