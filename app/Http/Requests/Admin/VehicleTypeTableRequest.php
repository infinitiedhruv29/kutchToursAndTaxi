<?php

namespace App\Http\Requests\Admin;

class VehicleTypeTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string { return 'sort_order'; }

    protected function sortableColumns(): array { return [0 => 'name', 2 => 'sort_order']; }
}
