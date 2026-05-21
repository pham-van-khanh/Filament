<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'alpha_dash', 'max:140', 'unique:section_types,slug'],
            'category' => ['required', 'string', 'max:80'],
            'default_data_schema' => ['required', 'array'],
            'default_style_schema' => ['nullable', 'array'],
            'available_variants' => ['required', 'array'],
        ];
    }
}

