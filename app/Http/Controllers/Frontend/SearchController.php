<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController
{
    public function __invoke(Request $request): View
    {
        $term = $request->string('q')->toString();

        $posts = Post::query()
            ->visibleToPublic()
            ->with(['detail', 'coverMedia', 'category', 'template'])
            ->search($term)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.pages.search', compact('posts', 'term'));
    }
}

