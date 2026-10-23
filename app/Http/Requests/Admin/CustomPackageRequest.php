<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CustomPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['nullable', 'string', 'regex:/^\+?[0-9\s\-()]{10,20}$/'],
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:255'],
            'highlight' => ['nullable', 'string', 'max:255'],
            'price_2p' => ['nullable', 'numeric', 'min:0'],
            'price_4p' => ['nullable', 'numeric', 'min:0'],
            'price_6p' => ['nullable', 'numeric', 'min:0'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['nullable', 'string', 'max:255'],
            'inclusions_text' => ['nullable', 'string'],
            'exclusions' => ['nullable', 'array'],
            'exclusions.*' => ['nullable', 'string', 'max:255'],
            'exclusions_text' => ['nullable', 'string'],
            'day_title' => ['nullable', 'array'],
            'day_description' => ['nullable', 'array'],
            'day_spots' => ['nullable', 'array'],
            'day_note' => ['nullable', 'array'],
            'action' => ['nullable', 'string', 'in:save,export'],
            'submit_action' => ['nullable', 'string', 'in:save,export'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_phone.regex' => 'The client contact number must be a valid phone number (10 to 20 digits, spaces, dashes, or parentheses allowed).',
        ];
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
                    'description' => isset($dayDescriptions[$i]) && trim((string) $dayDescriptions[$i]) !== '' ? trim((string) $dayDescriptions[$i]) : null,
                    'spots' => $spots,
                    'note' => isset($dayNotes[$i]) && trim((string) $dayNotes[$i]) !== '' ? trim((string) $dayNotes[$i]) : null,
                ];
            }
        }

        return $itineraries;
    }

    public function packageData(): array
    {
        $inclusions = $this->filled('inclusions_text')
            ? array_values(array_filter(array_map('trim', explode("\n", (string) $this->input('inclusions_text')))))
            : array_values(array_filter(array_map('trim', (array) $this->input('inclusions', []))));

        $exclusions = $this->filled('exclusions_text')
            ? array_values(array_filter(array_map('trim', explode("\n", (string) $this->input('exclusions_text')))))
            : array_values(array_filter(array_map('trim', (array) $this->input('exclusions', []))));

        return [
            'client_name' => $this->string('client_name')->trim()->value(),
            'client_phone' => $this->input('client_phone') ? $this->string('client_phone')->trim()->value() : null,
            'title' => $this->string('title')->trim()->value(),
            'duration' => $this->input('duration') ? $this->string('duration')->trim()->value() : null,
            'highlight' => $this->input('highlight') ? $this->string('highlight')->trim()->value() : null,
            'price_2p' => $this->filled('price_2p') ? (float) $this->input('price_2p') : null,
            'price_4p' => $this->filled('price_4p') ? (float) $this->input('price_4p') : null,
            'price_6p' => $this->filled('price_6p') ? (float) $this->input('price_6p') : null,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'itineraries' => $this->formatItineraries(),
        ];
    }
}
