<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class VehicleLookupRequest extends FormRequest
{
    abstract protected function lookupClass(): string;

    abstract protected function labelField(): string;

    abstract protected function parameter(): string;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $field = $this->labelField();
        if (is_string($this->input($field))) {
            $this->merge([$field => trim($this->input($field))]);
        }
    }

    public function rules(): array
    {
        $class = $this->lookupClass();
        $field = $this->labelField();
        $record = $this->route($this->parameter());

        return [
            $field => ['bail', 'required', 'string', 'max:255', Rule::unique((new $class)->getTable(), $field)->ignore($record instanceof Model ? $record->id : null),
                function (string $attribute, mixed $value, Closure $fail) use ($class, $field, $record): void {
                    if ($class::query()->whereRaw('LOWER(TRIM('.$field.')) = ?', [mb_strtolower($value)])
                        ->when($record instanceof Model, fn ($query) => $query->whereKeyNot($record->id))->exists()) {
                        $fail('This '.$field.' is already in use.');
                    }
                }],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }
}
