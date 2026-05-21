<?php

namespace App\Services\Builder;

use App\Models\SectionType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SectionDataValidator
{
    /**
     * @throws ValidationException
     */
    public function validate(string $type, array $data): array
    {
        $sectionType = SectionType::query()->where('slug', $type)->first();

        if (! $sectionType) {
            return $data;
        }

        $rules = collect(data_get($sectionType->default_data_schema, 'fields', []))
            ->mapWithKeys(function (array $field, string $name): array {
                $rule = match ($field['type'] ?? 'string') {
                    'integer' => ['integer'],
                    'array' => ['array'],
                    'url' => ['url'],
                    'html', 'string' => ['string'],
                    default => ['nullable'],
                };

                if ($field['required'] ?? false) {
                    array_unshift($rule, 'required');
                } else {
                    array_unshift($rule, 'nullable');
                }

                return [$name => $rule];
            })
            ->all();

        Validator::make($data, $rules)->validate();

        return $data;
    }
}

