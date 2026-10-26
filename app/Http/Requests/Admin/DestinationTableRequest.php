<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class DestinationTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string
    {
        return 'sort_order';
    }

    protected function sortableColumns(): array
    {
        return [0 => 'name', 2 => 'sort_order'];
    }

    public function rules(): array
    {
        return parent::rules() + [
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'featured' => ['nullable', Rule::in(['yes', 'no'])],
        ];
    }
}
