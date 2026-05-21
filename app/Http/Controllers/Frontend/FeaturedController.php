<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class FeaturedController
{
    public function __invoke(): View
    {
        $posts = Post::query()
            ->visibleToPublic()
            ->featured()
            ->with(['coverMedia', 'category', 'template'])
            ->latest('published_at')
            ->paginate(12);

        return view('frontend.pages.featured', compact('posts'));
    }
}

