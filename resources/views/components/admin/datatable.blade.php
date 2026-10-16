@props(['id', 'dataUrl', 'columns', 'rows', 'defaultOrder' => [[0, 'asc']], 'pageSize' => 15, 'emptyTitle' => 'No records found', 'emptyDescription' => 'Try adjusting your search or filters.'])
<section {{ $attributes->class(['min-w-0']) }} data-admin-datatable data-columns="{{ json_encode($columns) }}" data-url="{{ $dataUrl }}" data-order="{{ json_encode($defaultOrder) }}" data-page-size="{{ $pageSize }}" data-total="{{ $rows->total() }}" data-login-url="{{ route('admin.login') }}">
    <form method="GET" action="{{ url()->current() }}" data-table-form class="mb-5 flex flex-wrap items-end gap-3">
        <div>
            <label for="{{ $id }}-search" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Search</label>
            <input id="{{ $id }}-search" name="q" type="search" value="{{ request('q') }}" maxlength="255" data-table-search class="min-h-11 w-full rounded-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm focus:outline-2 focus:outline-brand-600">
        </div>
        {{ $filters ?? '' }}
        <input type="hidden" name="sort" value="{{ request('sort', $columns[$defaultOrder[0][0]]['sort']) }}">
        <input type="hidden" name="direction" value="{{ request('direction', $defaultOrder[0][1]) }}">
        <div>
            <label for="{{ $id }}-size" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Rows per page</label>
            <select id="{{ $id }}-size" name="per_page" data-table-size class="min-h-11 rounded-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm focus:outline-2 focus:outline-brand-600">
                @foreach ([15, 25, 50, 100] as $size)<option value="{{ $size }}" @selected((int) request('per_page', $pageSize) === $size)>{{ $size }}</option>@endforeach
            </select>
        </div>
        <x-admin.button variant="secondary" data-table-fallback>Filter</x-admin.button>
        <a href="{{ url()->current() }}" data-table-fallback class="min-h-11 rounded-lg px-3 py-3 text-sm text-slate-600 dark:text-slate-300 focus-visible:outline-2 focus-visible:outline-brand-600">Clear</a>
        <x-admin.button type="button" variant="secondary" data-table-clear class="hidden">Clear</x-admin.button>
        <x-admin.button type="button" variant="secondary" data-table-refresh class="hidden">Refresh</x-admin.button>
    </form>
    <div data-table-error role="alert" class="mb-4 hidden rounded-xl border border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-800">
        <span data-table-error-message>Unable to load records. Your current records have been retained.</span>
        <button type="button" data-table-retry class="ml-3 min-h-11 rounded-lg px-3 font-semibold underline focus-visible:outline-2 focus-visible:outline-red-600">Retry</button>
    </div>
    <p data-table-status role="status" aria-live="polite" class="mb-3 text-sm text-slate-600 dark:text-slate-300"></p>
    <x-admin.card>
        <div class="overflow-x-auto" role="region" aria-label="Scrollable records" tabindex="0">
            <table id="{{ $id }}" class="w-full text-left text-sm" aria-describedby="{{ $id }}-summary">
                <caption class="sr-only">{{ $label ?? 'Records' }}</caption>
                <thead class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    <tr>
                        @foreach ($columns as $index => $column)
                            <th scope="col" data-dt-order="disable" class="px-5 py-4">
                                @if ($column['orderable'] ?? false)
                                    <a data-table-fallback href="{{ request()->fullUrlWithQuery(['sort' => $column['sort'], 'direction' => request('sort') === $column['sort'] && request('direction', 'asc') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}" class="rounded-sm focus-visible:outline-2 focus-visible:outline-brand-600">{{ $column['label'] }}</a>
                                    <button type="button" data-table-sort="{{ $index }}" class="hidden min-h-11 items-center gap-2 rounded-sm font-medium focus-visible:outline-2 focus-visible:outline-brand-600">{{ $column['label'] }} <span data-sort-indicator aria-hidden="true">↕</span></button>
                                @else
                                    {{ $column['label'] }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
        <div data-table-empty class="{{ $rows->isEmpty() ? '' : 'hidden' }}">
            <x-admin.empty-state :title="$emptyTitle" :description="$emptyDescription" />
        </div>
    </x-admin.card>
    <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
        <p id="{{ $id }}-summary" data-table-summary class="text-sm text-slate-500 dark:text-slate-400">{{ $rows->total() }} records</p>
        <nav aria-label="Records pagination" data-table-pages class="hidden flex-wrap items-center gap-2"></nav>
        <div data-table-fallback>{{ $rows->links() }}</div>
    </div>
</section>
