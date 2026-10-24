<?php

namespace App\Http\Requests\Admin;

class TestimonialTableRequest extends DataTableRequest
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
            0 => 'client_name',
            1 => 'service_name',
            2 => 'is_published',
            3 => 'sort_order',
            4 => 'created_at',
        ];
    }
}
