<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GalleryImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'gallery_category_id' => ['required', 'integer', 'exists:gallery_categories,id'],
            'image' => [$isUpdate ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    public function contentData(): array
    {
        return [
            'gallery_category_id' => (int) $this->input('gallery_category_id'),
            'caption' => $this->input('caption') ? $this->string('caption')->trim()->value() : null,
            'image_alt' => $this->input('image_alt') ? $this->string('image_alt')->trim()->value() : null,
            'is_published' => $this->boolean('is_published'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
    }
}
