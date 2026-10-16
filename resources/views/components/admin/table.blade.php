<div class="overflow-x-auto">
    <table {{ $attributes->class(['w-full text-left text-sm']) }}>
        <thead class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/70 text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $head }}</thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">{{ $slot }}</tbody>
    </table>
</div>
