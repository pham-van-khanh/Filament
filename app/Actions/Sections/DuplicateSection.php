<?php

namespace App\Actions\Sections;

use App\Models\PostSection;

class DuplicateSection
{
    public function handle(PostSection $section): PostSection
    {
        $copy = $section->replicate();
        $copy->title = $section->title ? "{$section->title} Copy" : null;
        $copy->sort_order = ((int) $section->post->sections()->max('sort_order')) + 1;
        $copy->save();

        return $copy;
    }
}

