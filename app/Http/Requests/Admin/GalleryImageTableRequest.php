<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class GalleryImageTableRequest extends DataTableRequest
{
    protected function defaultSortColumn(): string
    {
        return 'sort_order';
    }

    protected function sortableColumns(): array
    {
        return [
            1 => 'caption',
            2 => 'gallery_category_id',
            3 => 'is_published',
            4 => 'sort_order',
        ];
    }

    public function rules(): array
    {
        return parent::rules() + [
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'category_id' => ['nullable', 'integer', 'exists:gallery_categories,id'],
        ];
    }
}
