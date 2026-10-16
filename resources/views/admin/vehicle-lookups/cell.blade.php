@if ($column === 'actions')
    <x-admin.record-actions :edit-url="route($module['prefix'].'.edit', [$record, 'return_to' => route($module['prefix'].'.index', request()->only(['q', 'sort', 'direction', 'page', 'per_page']))])" :delete-url="route($module['prefix'].'.destroy', $record)" :name="$record->getAttribute($module['field'])" :delete-title="'Delete '.strtolower($module['singular']).'?'" warning="This cannot be undone. Records used by vehicles cannot be deleted." />
@else
    {{ $record->getAttribute($column) }}
@endif
