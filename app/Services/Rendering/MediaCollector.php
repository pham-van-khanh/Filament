<?php

namespace App\Services\Rendering;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Support\Collection;

class MediaCollector
{
    public function forPost(Post $post): Collection
    {
        $ids = collect([
            $post->cover_media_id,
            $post->og_media_id,
        ]);

        foreach ($post->visibleSections as $section) {
            $ids = $ids
                ->push($section->media_id)
                ->merge($section->relationLoaded('items') ? $section->items->pluck('media_id') : $section->items()->pluck('media_id'));
        }

        return Media::query()
            ->whereIn('id', $ids->filter()->unique()->values())
            ->get()
            ->keyBy('id');
    }

}

