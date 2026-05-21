<?php

namespace App\Actions\Sections;

use App\Models\Post;

class SortSections
{
    public function handle(Post $post, array $orderedIds): void
    {
        foreach (array_values($orderedIds) as $index => $id) {
            $post->sections()
                ->whereKey($id)
                ->update(['sort_order' => $index + 1]);
        }
    }
}

