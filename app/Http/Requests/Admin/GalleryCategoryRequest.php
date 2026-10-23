<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $categoryId = $this->route('gallery_category')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('gallery_categories', 'name')->ignore($categoryId),
            ],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    public function contentData(): array
    {
        return [
            'name' => $this->string('name')->trim()->value(),
            'is_published' => $this->boolean('is_published'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
    }
}
