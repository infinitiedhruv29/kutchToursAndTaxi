<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class DataTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<int, string> */
    abstract protected function sortableColumns(): array;

    protected function defaultSortColumn(): string
    {
        return array_values($this->sortableColumns())[0];
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'draw' => ['sometimes', 'integer', 'min:0', 'max:2147483647'],
            'start' => ['sometimes', 'integer', 'min:0', 'max:2147483547'],
            'length' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
            'search' => ['sometimes', 'array:value,regex'],
            'search.value' => ['nullable', 'string', 'max:255'],
            'search.regex' => ['sometimes', 'declined'],
            'order' => ['sometimes', 'array', 'max:1'],
            'order.*' => ['array:column,dir,name'],
            'order.*.column' => ['required', 'integer', Rule::in(array_keys($this->sortableColumns()))],
            'order.*.dir' => ['required', Rule::in(['asc', 'desc'])],
            'columns' => ['sometimes', 'array', 'max:30'],
            'columns.*' => ['array'],
            'columns.*.search.value' => ['prohibited'],
            'columns.*.search.regex' => ['sometimes', 'declined'],
            'q' => ['nullable', 'string', 'max:255'],
            'sort' => ['sometimes', Rule::in(array_values($this->sortableColumns()))],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'page' => ['sometimes', 'integer', 'min:1', 'max:21474836'],
            'per_page' => ['sometimes', 'integer', Rule::in([15, 25, 50, 100])],
        ];
    }

    public function searchTerm(): string
    {
        return (string) $this->input('search.value', $this->input('q', ''));
    }

    public function sortColumn(): string
    {
        if ($this->has('order.0.column')) {
            return $this->sortableColumns()[(int) $this->input('order.0.column')];
        }

        return (string) $this->input('sort', $this->defaultSortColumn());
    }

    public function sortDirection(): string
    {
        return (string) $this->input('order.0.dir', $this->input('direction', 'asc'));
    }

    public function pageSize(): int
    {
        return (int) $this->input('length', $this->input('per_page', 15));
    }

    public function offset(): int
    {
        return (int) $this->input('start', ((int) $this->input('page', 1) - 1) * $this->pageSize());
    }
}
