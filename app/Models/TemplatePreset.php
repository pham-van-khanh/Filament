<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplatePreset extends Model
{
    protected $fillable = [
        'template_id',
        'name',
        'slug',
        'tokens',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'tokens' => 'array',
            'is_default' => 'boolean',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}

