<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostSectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_section_id',
        'media_id',
        'kind',
        'title',
        'subtitle',
        'value',
        'label',
        'time_label',
        'body',
        'caption',
        'url',
        'sort_order',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(PostSection::class, 'post_section_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
