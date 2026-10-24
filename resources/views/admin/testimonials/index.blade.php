<x-admin.layout title="Customer Testimonials" subtitle="Manage traveller reviews and guest feedback displayed on the home page.">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.testimonials.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">
            + Add guest testimonial
        </a>
    </div>

    <x-admin.datatable
        id="testimonials"
        :data-url="route('admin.testimonials.data')"
        :columns="$columns"
        :rows="$testimonials"
        :default-order="[[4, 'asc']]"
        empty-title="No testimonials found"
        empty-description="Add your first customer review to showcase traveller experiences."
    >
        <x-slot:label>Guest Testimonials</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="status" label="Publication status" data-table-filter>
                <option value="">All reviews</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </x-admin.select>
        </x-slot:filters>

        @foreach ($testimonials as $testimonial)
            <tr>
                @foreach ($columns as $definition)
                    <td class="px-5 py-5 text-slate-600 dark:text-slate-300">
                        @include('admin.testimonials.cell', ['column' => $definition['data']])
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-admin.datatable>

    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
