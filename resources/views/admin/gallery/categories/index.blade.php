<x-admin.layout title="Gallery Categories" subtitle="Organize your gallery photos into clean, browseable categories.">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.gallery-images.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
            &larr; Back to Photos
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6">
            <x-admin.alert type="error" data-validation-summary>
                {{ $errors->first() }}
            </x-admin.alert>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Add Category Form -->
        <div class="lg:col-span-1">
            <x-admin.card title="Add New Category">
                <form method="POST" action="{{ route('admin.gallery-categories.store') }}" data-admin-form data-loading-text="Adding category…" class="space-y-4 p-5 sm:p-6">
                    @csrf
                    <x-admin.field name="name" label="Category name" placeholder="e.g. White Rann, Heritage Sites" required maxlength="255" />
                    <x-admin.field name="sort_order" label="Display order" type="number" value="0" min="0" max="4294967295" step="1" required hint="Lower numbers appear first." />
                    <x-admin.checkbox name="is_published" label="Published" :checked="true" hint="Visible on public gallery filters." />
                    <div class="pt-2">
                        <x-admin.button class="w-full justify-center">Create Category</x-admin.button>
                    </div>
                </form>
            </x-admin.card>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <x-admin.card title="Existing Categories ({{ $categories->count() }})">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-semibold">Category Name</th>
                                <th scope="col" class="px-5 py-3 font-semibold">Photos</th>
                                <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                                <th scope="col" class="px-5 py-3 font-semibold">Order</th>
                                <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50">
                                    <td class="px-5 py-4 font-semibold text-slate-900 dark:text-slate-100">
                                        <div id="cat-display-{{ $category->id }}">
                                            {{ $category->name }}
                                        </div>
                                        <form id="cat-edit-form-{{ $category->id }}" method="POST" action="{{ route('admin.gallery-categories.update', $category) }}" class="hidden space-y-2 pt-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $category->name }}" required class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                            <div class="flex items-center gap-2">
                                                <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0" class="w-20 rounded-lg border border-slate-300 px-2 py-1 text-sm dark:border-slate-700 dark:bg-slate-800">
                                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400">
                                                    <input type="checkbox" name="is_published" value="1" @checked($category->is_published) class="rounded"> Published
                                                </label>
                                                <button type="submit" class="rounded bg-brand-700 px-2.5 py-1 text-xs font-semibold text-white hover:bg-brand-800">Save</button>
                                                <button type="button" onclick="document.getElementById('cat-edit-form-{{ $category->id }}').classList.add('hidden'); document.getElementById('cat-display-{{ $category->id }}').classList.remove('hidden');" class="text-xs text-slate-500">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            {{ $category->images_count }} {{ Str::plural('photo', $category->images_count) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-medium',
                                            'bg-brand-50 text-brand-800 dark:bg-brand-500/10 dark:text-brand-200' => $category->is_published,
                                            'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' => !$category->is_published,
                                        ])>
                                            {{ $category->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                                        {{ $category->sort_order }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="document.getElementById('cat-display-{{ $category->id }}').classList.add('hidden'); document.getElementById('cat-edit-form-{{ $category->id }}').classList.remove('hidden');" class="rounded-lg px-2 py-1 text-xs font-medium text-brand-700 hover:bg-brand-50 dark:text-brand-300 dark:hover:bg-brand-950/40">
                                                Edit
                                            </button>
                                            <button
                                                type="button"
                                                data-confirm-delete
                                                data-action="{{ route('admin.gallery-categories.destroy', $category) }}"
                                                data-title="{{ $category->name }}"
                                                data-delete-title="Delete category?"
                                                data-delete-warning="This category cannot be deleted if it has attached photos."
                                                class="rounded-lg px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/40"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                                        No categories created yet. Use the form on the left to add your first category.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
        </div>
    </div>

    <p data-delete-feedback role="alert" class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></p>
    <noscript><p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enable JavaScript to use deletion confirmations.</p></noscript>
</x-admin.layout>
