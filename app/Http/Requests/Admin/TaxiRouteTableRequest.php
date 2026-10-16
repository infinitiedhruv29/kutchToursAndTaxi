<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class TaxiRouteTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string
    {
        return 'sort_order';
    }

    protected function sortableColumns(): array
    {
        return [0 => 'title', 4 => 'sort_order'];
    }

    public function rules(): array
    {
        return parent::rules() + ['status' => ['nullable', Rule::in(['published', 'draft'])]];
    }
}
