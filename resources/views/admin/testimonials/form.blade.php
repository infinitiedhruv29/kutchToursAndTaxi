<x-admin.layout
    :title="$testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial'"
    subtitle="Manage verified customer feedback displayed on the home page."
>
    <form
        method="POST"
        action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
        data-admin-form
        data-loading-text="Saving testimonial…"
        class="space-y-6 max-w-3xl"
    >
        @csrf
        @if ($testimonial->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">

        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary>
                {{ $errors->first() }}
            </x-admin.alert>
        @endif

        <x-admin.card title="Guest Information & Review">
            <div class="space-y-5 p-5 sm:p-6">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field
                        name="client_name"
                        label="Client / Guest name"
                        :value="old('client_name', $testimonial->client_name)"
                        placeholder="e.g. Rahul & Neha Sharma"
                        required
                        maxlength="255"
                    />
                    <x-admin.field
                        name="service_name"
                        label="Tour / Package taken"
                        :value="old('service_name', $testimonial->service_name)"
                        placeholder="e.g. 3N4D White Rann Special"
                        hint="Displayed under customer name."
                        maxlength="255"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                        Customer Review Text <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="review"
                        rows="4"
                        required
                        placeholder="Write or paste the customer's review here..."
                        class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 focus:border-brand-600 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >{{ old('review', $testimonial->review) }}</textarea>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field
                        name="youtube_link"
                        label="YouTube Video Link (Optional)"
                        :value="old('youtube_link', $testimonial->youtube_link)"
                        placeholder="https://youtu.be/..."
                        hint="Optional video review link."
                        maxlength="500"
                    />
                    <x-admin.field
                        name="sort_order"
                        label="Sort display order"
                        type="number"
                        :value="old('sort_order', $testimonial->sort_order ?? 0)"
                        hint="Lower numbers appear first."
                        required
                        min="0"
                    />
                </div>

                <div class="pt-2">
                    <x-admin.checkbox
                        name="is_published"
                        label="Published on website"
                        hint="When enabled, this review will be displayed in the guest reviews section on the home page."
                        :checked="old('is_published', $testimonial->is_published ?? true)"
                    />
                </div>
            </div>
        </x-admin.card>

        <div class="flex items-center justify-between gap-4 pt-2">
            <a href="{{ $returnTo }}" class="rounded-lg px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                Cancel
            </a>
            <button
                type="submit"
                class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600"
            >
                {{ $testimonial->exists ? 'Update Testimonial' : 'Create Testimonial' }}
            </button>
        </div>
    </form>
</x-admin.layout>
