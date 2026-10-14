@props(['title' => null, 'description' => null])
<section {{ $attributes->class(['min-w-0 rounded-2xl border border-slate-200 bg-white shadow-xs']) }}>
    @if ($title)
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
            @if ($description)<p class="mt-1 text-sm text-slate-500">{{ $description }}</p>@endif
        </div>
    @endif
    {{ $slot }}
</section>
