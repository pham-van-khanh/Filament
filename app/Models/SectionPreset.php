<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionPreset extends Model
{
    protected $fillable = [
        'section_type_id',
        'name',
        'slug',
        'data',
        'style',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'style' => 'array',
            'settings' => 'array',
        ];
    }

    public function sectionType(): BelongsTo
    {
        return $this->belongsTo(SectionType::class);
    }
}

