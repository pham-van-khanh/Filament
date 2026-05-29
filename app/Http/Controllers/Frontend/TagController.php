<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Tag;
use Illuminate\Contracts\View\View;

class TagController
{
    public function __invoke(Tag $tag): View
    {
        $posts = $tag->posts()
            ->visibleToPublic()
            ->with(['detail', 'coverMedia', 'category', 'template'])
            ->latest('memory_date')
            ->paginate(12);

        return view('frontend.pages.tag', compact('tag', 'posts'));
    }
}

