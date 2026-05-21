<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'alpha_dash', 'max:140', 'unique:templates,slug'],
            'design_tokens' => ['required', 'array'],
            'layout_config' => ['required', 'array'],
            'supported_section_types' => ['nullable', 'array'],
        ];
    }
}

