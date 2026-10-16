<?php

namespace App\Http\Requests\Admin;

use App\Models\TaxiRoute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

abstract class TaxiRouteRequest extends FormRequest
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

    public function slugBase(): string
    {
        return TaxiRoute::suggestedSlug($this->validated('origin_name'), $this->validated('destination_name'));
    }

    protected function prepareForValidation(): void
    {
        $origin = $this->input('origin_name');
        $destination = $this->input('destination_name');

        if (! ($this->route('taxiRoute') instanceof TaxiRoute) && is_string($origin) && is_string($destination)) {
            if (! $this->filled('title')) {
                $this->merge(['title' => $origin.' to '.$destination]);
            }

            if ($this->boolean('auto_slug') || ! $this->filled('slug')) {
                $this->generatedSlug = true;
                $this->merge(['slug' => TaxiRoute::uniqueSlug(TaxiRoute::suggestedSlug($origin, $destination))]);
            }
        }
    }

    public function rules(): array
    {
        $route = $this->route('taxiRoute');
        $fareRules = ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999.99'];

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('taxi_routes', 'slug')->ignore($route instanceof TaxiRoute ? $route->id : null)],
            'auto_slug' => ['sometimes', 'boolean'],
            'origin_name' => ['required', 'string', 'max:255'],
            'destination_name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:100000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'distance_km' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'duration_hours' => ['nullable', 'integer', 'min:0', 'max:71582788'],
            'duration_minutes' => ['nullable', 'integer', 'between:0,59'],
            'forward_one_way_fare' => $fareRules,
            'forward_round_trip_fare' => $fareRules,
            'reverse_one_way_fare' => $fareRules,
            'reverse_round_trip_fare' => $fareRules,
            'reverse_enabled' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if (! $validator->errors()->hasAny(['origin_name', 'destination_name'])
                && Str::lower($this->string('origin_name')->trim()->toString()) === Str::lower($this->string('destination_name')->trim()->toString())) {
                $validator->errors()->add('destination_name', 'The origin and destination must be different.');
            }

            if (! $validator->errors()->hasAny(['duration_hours', 'duration_minutes'])
                && ((int) $this->input('duration_hours') * 60 + (int) $this->input('duration_minutes')) > 4294967295) {
                $validator->errors()->add('duration_hours', 'The total duration is too large.');
            }
        }];
    }

    public function routeData(): array
    {
        $fields = [
            'title', 'slug', 'origin_name', 'destination_name', 'short_description', 'description',
            'image_alt', 'distance_km', 'forward_one_way_fare', 'forward_round_trip_fare',
            'reverse_one_way_fare', 'reverse_round_trip_fare', 'seo_title', 'seo_description',
        ];
        $data = array_replace(array_fill_keys($fields, null), $this->safe()->only($fields));
        foreach (['reverse_enabled', 'is_published', 'is_featured'] as $field) {
            $data[$field] = $this->boolean($field);
        }
        $data['sort_order'] = $this->validated('sort_order') ?? 0;
        $data['estimated_duration_minutes'] = $this->filled('duration_hours') || $this->filled('duration_minutes')
            ? (int) $this->validated('duration_hours') * 60 + (int) $this->validated('duration_minutes') : null;

        if (! $data['reverse_enabled']) {
            unset($data['reverse_one_way_fare'], $data['reverse_round_trip_fare']);
        }

        return $data;
    }
}
