<x-admin.layout
    :title="$image->exists ? 'Edit photo' : 'Upload photo'"
    subtitle="Add high quality photos with captions, category tagging, and alt text."
>
    <form
        method="POST"
        action="{{ $image->exists ? route('admin.gallery-images.update', $image) : route('admin.gallery-images.store') }}"
        enctype="multipart/form-data"
        data-admin-form
        data-loading-text="Saving photo…"
        class="space-y-6"
    >
        @csrf
        @if ($image->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">

        @if ($errors->any())
            <x-admin.alert type="error" data-validation-summary>Please check the highlighted fields.</x-admin.alert>
        @endif

        <x-admin.card title="Photo file">
            <div class="space-y-5 p-5 sm:p-6">
                <x-admin.image-upload
                    name="image"
                    :path="$image->image_path"
                    label="Photo image"
                    preview-alt="Gallery photo preview"
                />
            </div>
        </x-admin.card>

        <x-admin.card title="Photo details">
            <div class="space-y-5 p-5 sm:p-6">
                @if ($categories->isEmpty())
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                        <p class="font-semibold">No categories exist yet</p>
                        <p class="mt-1">Photos must belong to a category. Please <a href="{{ route('admin.gallery-categories.index') }}" class="font-semibold underline hover:text-amber-900 dark:hover:text-amber-100">create a category here &rarr;</a> before uploading.</p>
                    </div>
                @endif
                <div>
                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Choose which collection this photo belongs to</span>
                        <a href="{{ route('admin.gallery-categories.index') }}" target="_blank" class="text-xs font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">
                            + Add / Manage categories
                        </a>
                    </div>
                    <x-admin.select name="gallery_category_id" label="Category" required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('gallery_category_id', $image->gallery_category_id) === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-admin.select>
                </div>
                <x-admin.field name="caption" label="Caption" :value="$image->caption" maxlength="255" hint="Optional caption displayed beneath the photo." />
                <x-admin.field name="image_alt" label="Alternative text (SEO & Accessibility)" :value="$image->image_alt" maxlength="255" hint="Descriptive text for search engines and screen readers." />
            </div>
        </x-admin.card>

        <x-admin.card title="Visibility and order">
            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <x-admin.checkbox name="is_published" label="Published" :checked="$image->is_published" hint="Leave unchecked to keep photo in draft mode." />
                <x-admin.field name="sort_order" label="Display order" type="number" :value="$image->sort_order ?? 0" min="0" max="4294967295" step="1" required hint="Lower numbers appear first." />
            </div>
        </x-admin.card>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ $returnTo }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300">Cancel</a>
            <x-admin.button>Save photo</x-admin.button>
        </div>
    </form>
</x-admin.layout>
