@props(['title' => null, 'description' => null])
<section {{ $attributes->class(['min-w-0 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-xs']) }}>
    @if ($title)
        <div class="border-b border-slate-100 dark:border-slate-800 px-5 py-5 sm:px-6">
            <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</h2>
            @if ($description)<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>@endif
        </div>
    @endif
    {{ $slot }}
</section>
