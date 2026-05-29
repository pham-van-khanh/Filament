<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'media_id',
        'headline',
        'body',
        'quote_text',
        'quote_author',
        'caption',
        'url',
        'height',
        'layout',
        'autoplay',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'autoplay' => 'boolean',
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

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PostSectionItem::class)->orderBy('sort_order');
    }

    public function getComponentTypeAttribute(): string
    {
        return str($this->type)->kebab()->toString();
    }

    public function getComponentVariantAttribute(): ?string
    {
        return $this->variant ? str($this->variant)->kebab()->toString() : null;
    }

    public function getRenderDataAttribute(): array
    {
        $post = $this->relationLoaded('post') ? $this->getRelation('post') : null;
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        $mediaIds = $items
            ->pluck('media_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $itemRows = $items
            ->map(fn (PostSectionItem $item): array => [
                'media_id' => $item->media_id ? (int) $item->media_id : null,
                'title' => $item->title,
                'subtitle' => $item->subtitle,
                'value' => $item->value,
                'label' => $item->label,
                'time' => $item->time_label,
                'body' => $item->body,
                'caption' => $item->caption,
                'url' => $item->url,
            ])
            ->values()
            ->all();

        return match ($this->type) {
            'hero_image' => [
                'media_id' => $this->media_id,
                'headline' => $this->headline,
                'date_range' => $post?->date_range,
            ],
            'stats' => [
                'items' => $items->map(fn (PostSectionItem $item): array => [$item->value, $item->label])->values()->all(),
            ],
            'gallery_grid' => [
                'media_ids' => $mediaIds,
                'items' => $itemRows,
                'layout' => $this->layout ?: 'mosaic',
            ],
            'gallery_slider' => [
                'slides' => $itemRows,
                'layout' => $this->layout ?: 'carousel',
                'autoplay' => $this->autoplay,
            ],
            'single_image' => [
                'media_id' => $this->media_id,
                'caption' => $this->caption,
            ],
            'image_text' => [
                'media_id' => $this->media_id,
                'body' => $this->body,
                'caption' => $this->caption,
            ],
            'quote' => [
                'quote' => $this->quote_text,
                'author' => $this->quote_author,
            ],
            'rich_text' => [
                'html' => $this->body,
            ],
            'video_embed' => [
                'media_id' => $this->media_id,
                'url' => $this->url,
                'caption' => $this->caption,
                'layout' => $this->layout,
            ],
            'music' => [
                'enabled' => $this->is_visible,
                'autoplay' => $this->autoplay,
                'loop' => true,
                'title' => $this->headline,
                'artist' => $this->subtitle,
                'url' => $this->url,
                'src' => $this->url,
                'caption' => $this->caption,
            ],
            'timeline' => [
                'items' => $itemRows,
            ],
            'ending' => [
                'title' => $this->headline,
                'body' => $this->body,
            ],
            default => [
                'media_id' => $this->media_id,
                'body' => $this->body,
                'caption' => $this->caption,
                'url' => $this->url,
            ],
        };
    }

    public function getRenderStyleAttribute(): array
    {
        $style = [];

        if ($this->height) {
            $style['--section-height'] = $this->height;
        }

        return $style;
    }
}

