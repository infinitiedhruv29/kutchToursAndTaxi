<?php

namespace App\Http\Requests\Admin;

use App\Models\Faq;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:1000'],
            'answer' => ['required', 'string', 'max:100000'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if (! $validator->errors()->has('answer')) {
                $text = Faq::answerText(Faq::sanitizeAnswer($this->input('answer')));
                if (! preg_match('/[^\s\p{Z}\p{Cf}]/u', $text)) {
                    $validator->errors()->add('answer', 'Please enter a meaningful answer. Empty formatting and unsupported content are not sufficient.');
                }
            }
        }];
    }

    public function contentData(): array
    {
        return $this->safe()->only(['question', 'answer', 'sort_order']) + ['is_published' => $this->boolean('is_published')];
    }
}
