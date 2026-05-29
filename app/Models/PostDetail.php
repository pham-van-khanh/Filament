<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'date_range',
        'music_enabled',
        'music_url',
        'music_title',
        'music_artist',
    ];

    protected function casts(): array
    {
        return [
            'music_enabled' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
