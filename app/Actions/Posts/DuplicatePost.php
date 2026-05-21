<?php

namespace App\Actions\Posts;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Str;

class DuplicatePost
{
    public function handle(Post $post): Post
    {
        $copy = $post->replicate([
            'slug',
            'published_at',
            'unlisted_token',
        ]);

        $copy->title = "{$post->title} Copy";
        $copy->slug = Str::slug($copy->title).'-'.Str::lower(Str::random(6));
        $copy->status = PostStatus::Draft;
        $copy->published_at = null;
        $copy->unlisted_token = Str::random(40);
        $copy->is_featured = false;
        $copy->save();

        foreach ($post->sections as $section) {
            $newSection = $section->replicate();
            $newSection->post_id = $copy->id;
            $newSection->save();
        }

        $copy->tags()->sync($post->tags()->pluck('tags.id'));

        return $copy;
    }
}

