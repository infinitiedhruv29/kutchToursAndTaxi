<?php

namespace App\Http\Requests\Admin;

class CustomPackageTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string
    {
        return 'created_at';
    }

    public function sortDirection(): string
    {
        return (string) $this->input('order.0.dir', $this->input('direction', 'desc'));
    }

    protected function sortableColumns(): array
    {
        return [
            0 => 'client_name',
            1 => 'title',
            2 => 'duration',
            3 => 'price_2p',
            4 => 'created_at',
        ];
    }
}
