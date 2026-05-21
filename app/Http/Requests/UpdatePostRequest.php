<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('posts', 'slug')->ignore($post)],
            'template_id' => ['required', 'exists:templates,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'cover_media_id' => ['nullable', 'exists:media,id'],
            'status' => ['required', Rule::enum(PostStatus::class)],
            'visibility' => ['required', Rule::enum(PostVisibility::class)],
            'memory_date' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'sections' => ['nullable', 'array'],
            'sections.*.type' => ['required_with:sections', 'string', 'exists:section_types,slug'],
            'sections.*.variant' => ['nullable', 'string', 'max:120'],
            'sections.*.data' => ['required_with:sections', 'array'],
            'sections.*.style' => ['nullable', 'array'],
        ];
    }
}

