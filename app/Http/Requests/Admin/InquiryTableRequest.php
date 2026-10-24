<?php

namespace App\Http\Requests\Admin;

class InquiryTableRequest extends DataTableRequest
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
            0 => 'name',
            1 => 'phone',
            2 => 'service_type',
            3 => 'status',
            4 => 'created_at',
        ];
    }
}
