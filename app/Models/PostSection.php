<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'section_type_id',
        'type',
        'title',
        'subtitle',
        'variant',
        'data',
        'style',
        'settings',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'style' => 'array',
            'settings' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function sectionType(): BelongsTo
    {
        return $this->belongsTo(SectionType::class);
    }

    public function getComponentTypeAttribute(): string
    {
        return str($this->type)->kebab()->toString();
    }

    public function getComponentVariantAttribute(): ?string
    {
        return $this->variant ? str($this->variant)->kebab()->toString() : null;
    }
}

