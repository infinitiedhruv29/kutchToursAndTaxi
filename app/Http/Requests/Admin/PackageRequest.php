<?php

namespace App\Http\Requests\Admin;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    protected bool $generatedSlug = false;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function generatedSlug(): bool
    {
        return $this->generatedSlug;
    }

    protected function prepareForValidation(): void
    {
        $package = $this->route('package');
        $ignoreId = $package instanceof Package ? $package->id : null;

        if (is_string($this->input('title')) && ($this->boolean('auto_slug') || ! $this->filled('slug'))) {
            $this->generatedSlug = true;
            $this->merge(['slug' => Package::uniqueSlug(Package::suggestedSlug($this->input('title')), $ignoreId)]);
        }
    }

    public function rules(): array
    {
        $package = $this->route('package');
        $packageId = $package instanceof Package ? $package->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('packages', 'slug')->ignore($packageId),
            ],
            'auto_slug' => ['sometimes', 'boolean'],
            'package_type' => ['required', 'string', 'in:Kutch,Outstation'],
            'duration' => ['nullable', 'string', 'max:255'],
            'highlight' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_2p' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
            'price_4p' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
            'price_6p' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'remove_image' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:2000'],
            'day_title' => ['nullable', 'array'],
            'day_description' => ['nullable', 'array'],
            'day_spots' => ['nullable', 'array'],
            'day_note' => ['nullable', 'array'],
            'inclusions_text' => ['nullable', 'string'],
            'exclusions_text' => ['nullable', 'string'],
        ];
    }

    public function packageData(): array
    {
        $fields = [
            'title',
            'slug',
            'package_type',
            'duration',
            'highlight',
            'description',
            'image_alt',
            'price_2p',
            'price_4p',
            'price_6p',
            'sort_order',
            'seo_title',
            'seo_description',
        ];

        return array_replace(array_fill_keys($fields, null), $this->safe()->only($fields), [
            'is_published' => $this->boolean('is_published'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function formatItineraries(): array
    {
        $itineraries = [];
        $dayTitles = $this->input('day_title', []);
        $dayDescriptions = $this->input('day_description', []);
        $daySpots = $this->input('day_spots', []);
        $dayNotes = $this->input('day_note', []);

        for ($i = 0; $i < count($dayTitles); $i++) {
            if (isset($dayTitles[$i]) && trim((string) $dayTitles[$i]) !== '') {
                $spots = [];
                if (isset($daySpots[$i]) && is_string($daySpots[$i])) {
                    $spots = array_values(array_filter(array_map('trim', explode("\n", $daySpots[$i]))));
                }

                $itineraries[] = [
                    'day_number' => count($itineraries) + 1,
                    'title' => trim((string) $dayTitles[$i]),
                    'description' => isset($dayDescriptions[$i]) ? trim((string) $dayDescriptions[$i]) : null,
                    'spots' => $spots,
                    'note' => isset($dayNotes[$i]) ? trim((string) $dayNotes[$i]) : null,
                ];
            }
        }

        return $itineraries;
    }

    public function formatInclusions(): array
    {
        $text = (string) $this->input('inclusions_text', '');
        return array_values(array_filter(array_map('trim', explode("\n", $text))));
    }

    public function formatExclusions(): array
    {
        $text = (string) $this->input('exclusions_text', '');
        return array_values(array_filter(array_map('trim', explode("\n", $text))));
    }
}
