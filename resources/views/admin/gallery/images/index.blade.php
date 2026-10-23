<x-admin.layout title="Gallery Photos" subtitle="Manage gallery photos, captions, categories, and visibility.">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('admin.gallery-categories.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
            Manage categories
        </a>
        <a href="{{ route('admin.gallery-images.create', ['return_to' => request()->fullUrl()]) }}" data-return-link class="inline-flex min-h-11 items-center rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600">
            Add photo
        </a>
    </div>

    <x-admin.datatable
        id="gallery-images"
        :data-url="route('admin.gallery-images.data')"
        :columns="$columns"
        :rows="$images"
        :default-order="[[4, 'asc']]"
        empty-title="No photos found"
        empty-description="Upload your first photo or adjust the search and category filters."
    >
        <x-slot:label>Photos</x-slot:label>
        <x-slot:filters>
            <x-admin.select name="category_id" label="Category" data-table-filter>
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-admin.select>
            <x-admin.select name="status" label="Publication status" data-table-filter>
                <option value="">All photos</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </x-admin.select>
        </x-slot:filters>
        @foreach ($images as $image)
            <tr>
                @foreach ($columns as $definition)
                    <td class="px-5 py-5 text-slate-600 dark:text-slate-300">
                        @include('admin.gallery.images.cell', ['column' => $definition['data']])
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-admin.datatable>

    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
