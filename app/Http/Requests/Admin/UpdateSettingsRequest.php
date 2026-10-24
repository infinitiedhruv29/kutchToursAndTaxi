<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        if (is_string($this->input('whatsapp_number'))) {
            $number = preg_replace('/[\s()+-]/', '', $this->input('whatsapp_number'));
            $normalized['whatsapp_number'] = $number === '' ? null : $number;
        }

        foreach (['general_inclusions', 'general_exclusions'] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $value = preg_split('/\r\n|\r|\n/', $value);
            }

            if (is_array($value) && array_is_list($value)) {
                $items = array_values(array_filter(
                    array_map(fn (mixed $item): mixed => is_string($item) ? trim($item) : $item, $value),
                    fn (mixed $item): bool => $item !== '' && $item !== null,
                ));
                $normalized[$field] = $items === [] ? null : $items;
            }
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        return [
            'business_name' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'contact_number_2' => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'regex:/^[1-9][0-9]{7,14}$/'],
            'contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:5000'],
            'google_map_link' => ['nullable', 'string', 'url:http,https', 'max:2048'],
            'instagram_link' => ['nullable', 'string', 'url:http,https', 'max:2048'],
            'facebook_link' => ['nullable', 'string', 'url:http,https', 'max:2048'],
            'youtube_link' => ['nullable', 'string', 'url:http,https', 'max:2048'],
            'gst_number' => ['nullable', 'string', 'max:30'],
            'general_inclusions' => ['nullable', 'array', 'list', 'max:100'],
            'general_inclusions.*' => ['required', 'string', 'max:500'],
            'general_exclusions' => ['nullable', 'array', 'list', 'max:100'],
            'general_exclusions.*' => ['required', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'dark_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png', 'max:512'],
            'remove_logo' => ['sometimes', 'boolean'],
            'remove_dark_logo' => ['sometimes', 'boolean'],
            'remove_favicon' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'primary_color.regex' => 'The primary color must be a valid hex color code (e.g. #9E2A2B).',
            'whatsapp_number.regex' => 'Enter a WhatsApp number with its country code, using 8–15 digits and no leading zero.',
            'general_inclusions.max' => 'Enter no more than 100 inclusions.',
            'general_exclusions.max' => 'Enter no more than 100 exclusions.',
            'general_inclusions.*.max' => 'Each inclusion must be no longer than 500 characters.',
            'general_exclusions.*.max' => 'Each exclusion must be no longer than 500 characters.',
        ];
    }
}
