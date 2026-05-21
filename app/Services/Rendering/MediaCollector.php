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
            $ids = $ids->merge($this->collectIds($section->data ?? []));
        }

        return Media::query()
            ->whereIn('id', $ids->filter()->unique()->values())
            ->get()
            ->keyBy('id');
    }

    protected function collectIds(mixed $value): array
    {
        $ids = [];

        if (! is_array($value)) {
            return $ids;
        }

        foreach ($value as $key => $item) {
            if (in_array($key, ['media_id', 'image_id', 'video_id', 'audio_id', 'poster_id', 'cover_media_id'], true) && is_numeric($item)) {
                $ids[] = (int) $item;
            }

            if (in_array($key, ['media_ids', 'images', 'videos'], true) && is_array($item)) {
                foreach ($item as $id) {
                    if (is_numeric($id)) {
                        $ids[] = (int) $id;
                    }
                }
            }

            if (is_array($item)) {
                $ids = array_merge($ids, $this->collectIds($item));
            }
        }

        return $ids;
    }
}

