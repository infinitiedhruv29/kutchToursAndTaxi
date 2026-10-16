@props(['title', 'description'])
<div {{ $attributes->class(['flex flex-col items-center px-6 py-14 text-center']) }}>
    <span class="mb-4 flex size-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400"><x-admin.icon name="inbox" /></span>
    <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</h3>
    <p class="mt-2 max-w-sm text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $description }}</p>
</div>
