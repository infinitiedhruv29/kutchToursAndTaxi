<?php

namespace App\Http\Requests\Admin;

class VehicleCapacityTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string { return 'sort_order'; }

    protected function sortableColumns(): array { return [0 => 'label', 1 => 'passenger_capacity', 3 => 'sort_order']; }
}
