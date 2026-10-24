<?php

namespace App\Http\Requests\Admin;

use App\Models\Testimonial;
use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'service_name' => ['nullable', 'string', 'max:255'],
            'review' => ['required', 'string', 'max:2000'],
            'youtube_link' => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    public function testimonialData(): array
    {
        $youtubeLink = $this->input('youtube_link') ? trim((string) $this->input('youtube_link')) : null;
        $videoId = Testimonial::parseYoutubeId($youtubeLink);

        return [
            'client_name' => trim((string) $this->input('client_name')),
            'service_name' => $this->input('service_name') ? trim((string) $this->input('service_name')) : null,
            'review' => trim((string) $this->input('review')),
            'youtube_link' => $youtubeLink,
            'youtube_video_id' => $videoId,
            'is_published' => $this->boolean('is_published'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
    }
}
