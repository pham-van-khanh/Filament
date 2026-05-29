<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'disk',
        'type',
        'mime_type',
        'filename',
        'original_name',
        'path',
        'url',
        'alt',
        'caption',
        'width',
        'height',
        'duration',
        'size',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'type' => MediaType::class,
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Media $media): void {
            if (! $media->path && $media->url) {
                $media->path = 'external/'.Str::slug(pathinfo(parse_url($media->url, PHP_URL_PATH) ?: 'remote-media', PATHINFO_FILENAME)).'-'.Str::random(6);
            }

            if (! $media->filename && $media->path) {
                $media->filename = basename($media->path);
            }

            if (! $media->original_name) {
                $media->original_name = $media->filename ?: Str::random(12);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_media')
            ->withPivot(['role', 'sort_order'])
            ->withTimestamps();
    }

    public function getDisplayUrlAttribute(): string
    {
        return $this->url ?: Storage::disk($this->disk)->url($this->path);
    }
}
