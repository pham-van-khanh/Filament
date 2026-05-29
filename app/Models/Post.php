<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'template_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'cover_media_id',
        'status',
        'visibility',
        'password',
        'unlisted_token',
        'published_at',
        'memory_date',
        'location_name',
        'mood',
        'is_featured',
        'seo_title',
        'seo_description',
        'og_media_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'visibility' => PostVisibility::class,
            'published_at' => 'datetime',
            'memory_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post): void {
            if (! $post->slug && $post->title) {
                $post->slug = Str::slug($post->title);
            }

            if (! $post->unlisted_token) {
                $post->unlisted_token = Str::random(40);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function ogMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_media_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PostSection::class)->orderBy('sort_order');
    }

    public function visibleSections(): HasMany
    {
        return $this->sections()->where('is_visible', true);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function privateMessages(): HasMany
    {
        return $this->hasMany(PrivateMessage::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'post_media')
            ->withPivot(['role', 'sort_order'])
            ->withTimestamps();
    }

    public function detail(): HasOne
    {
        return $this->hasOne(PostDetail::class);
    }

    public function getDateRangeAttribute(): ?string
    {
        return $this->relationLoaded('detail') ? $this->detail?->date_range : null;
    }

    public function getMusicEnabledAttribute(): bool
    {
        return (bool) ($this->relationLoaded('detail') ? $this->detail?->music_enabled : false);
    }

    public function getMusicUrlAttribute(): ?string
    {
        return $this->relationLoaded('detail') ? $this->detail?->music_url : null;
    }

    public function getMusicTitleAttribute(): ?string
    {
        return $this->relationLoaded('detail') ? $this->detail?->music_title : null;
    }

    public function getMusicArtistAttribute(): ?string
    {
        return $this->relationLoaded('detail') ? $this->detail?->music_artist : null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PostStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeVisibleToPublic(Builder $query): Builder
    {
        return $query->published()->where('visibility', PostVisibility::Public);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', PostStatus::Draft);
    }

    public function scopePrivate(Builder $query): Builder
    {
        return $query->where('visibility', PostVisibility::Private);
    }

    public function scopeByTemplate(Builder $query, ?string $slug): Builder
    {
        return $query->when($slug, fn (Builder $q) => $q->whereHas('template', fn (Builder $template) => $template->where('slug', $slug)));
    }

    public function scopeByCategory(Builder $query, ?string $slug): Builder
    {
        return $query->when($slug, fn (Builder $q) => $q->whereHas('category', fn (Builder $category) => $category->where('slug', $slug)));
    }

    public function scopeByTag(Builder $query, ?string $slug): Builder
    {
        return $query->when($slug, fn (Builder $q) => $q->whereHas('tags', fn (Builder $tag) => $tag->where('slug', $slug)));
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $q) use ($term): void {
            $q->where(function (Builder $sub) use ($term): void {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%");
            });
        });
    }

    public function getPublicUrlAttribute(): string
    {
        return route('memories.show', $this->slug);
    }
}
