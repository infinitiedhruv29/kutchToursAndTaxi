<?php

namespace App\Http\Requests\Admin;

class PackageTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string
    {
        return 'sort_order';
    }

    public function sortDirection(): string
    {
        return (string) $this->input('order.0.dir', $this->input('direction', 'asc'));
    }

    protected function sortableColumns(): array
    {
        return [
            0 => 'title',
            1 => 'package_type',
            2 => 'duration',
            3 => 'price_2p',
            4 => 'is_published',
            5 => 'sort_order',
            6 => 'created_at',
        ];
    }
}
