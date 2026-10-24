<?php

namespace App\Http\Requests\Admin;

class InvoiceTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string
    {
        return 'invoice_date';
    }

    public function sortDirection(): string
    {
        return (string) $this->input('order.0.dir', $this->input('direction', 'desc'));
    }

    protected function sortableColumns(): array
    {
        return [
            0 => 'invoice_number',
            1 => 'customer_name',
            2 => 'invoice_date',
            4 => 'total_amount',
        ];
    }
}
