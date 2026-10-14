<div class="overflow-x-auto">
    <table {{ $attributes->class(['w-full text-left text-sm']) }}>
        <thead class="border-b border-slate-100 bg-slate-50/70 text-xs font-medium uppercase tracking-wider text-slate-500">{{ $head }}</thead>
        <tbody class="divide-y divide-slate-100">{{ $slot }}</tbody>
    </table>
</div>
