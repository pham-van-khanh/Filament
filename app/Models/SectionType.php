<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'icon',
        'preview_media_id',
        'default_data_schema',
        'default_style_schema',
        'available_variants',
        'supported_templates',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'default_data_schema' => 'array',
            'default_style_schema' => 'array',
            'available_variants' => 'array',
            'supported_templates' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function previewMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'preview_media_id');
    }

    public function postSections(): HasMany
    {
        return $this->hasMany(PostSection::class);
    }

}

