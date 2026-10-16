<x-admin.layout :title="$destination->exists ? 'Edit destination' : 'Create destination'" subtitle="Manage the name, image, and short description shown on destination cards.">
    <form method="POST" action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}" enctype="multipart/form-data" data-admin-form data-loading-text="Saving destination…" class="space-y-6">
        @csrf @if ($destination->exists) @method('PUT') @endif
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
        @if ($errors->any())<x-admin.alert type="error" data-validation-summary>Please check the highlighted fields.</x-admin.alert>@endif
        <x-admin.card title="Destination details">
            <div class="space-y-5 p-5 sm:p-6"><x-admin.field name="name" label="Destination name" :value="$destination->name" maxlength="255" required /><x-admin.textarea name="short_description" label="Short description" :value="$destination->short_description" maxlength="2000" hint="Optional plain text, up to 2,000 characters. No separate detail page is created." /></div>
        </x-admin.card>
        <x-admin.card title="Image">
            <div class="space-y-5 p-5 sm:p-6"><x-admin.image-upload :path="$destination->image_path" label="Destination image" preview-alt="Destination image preview" /><x-admin.field name="image_alt" label="Image alternative text" :value="$destination->image_alt" maxlength="255" /></div>
        </x-admin.card>
        <x-admin.card title="Visibility and order">
            <div class="grid gap-5 p-5 sm:grid-cols-3 sm:p-6"><x-admin.checkbox name="is_published" label="Published" :checked="$destination->is_published" hint="Leave unchecked to save as a draft." /><x-admin.checkbox name="is_featured" label="Featured" :checked="$destination->is_featured" hint="A featured draft remains unpublished." /><x-admin.field name="sort_order" label="Display order" type="number" :value="$destination->sort_order ?? 0" min="0" max="4294967295" step="1" required hint="Lower numbers appear first." /></div>
        </x-admin.card>
        <div class="flex flex-wrap items-center justify-between gap-4"><a href="{{ $returnTo }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 focus-visible:outline-2 focus-visible:outline-brand-600 dark:text-slate-300">Cancel</a><x-admin.button>Save destination</x-admin.button></div>
    </form>
</x-admin.layout>
