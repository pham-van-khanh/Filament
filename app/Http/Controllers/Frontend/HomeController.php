<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class HomeController
{
    public function __invoke(): View
    {
        $featured = Post::query()
            ->visibleToPublic()
            ->featured()
            ->with(['coverMedia', 'category', 'template'])
            ->withCount('media')
            ->latest('published_at')
            ->take(6)
            ->get();

        $latest = Post::query()
            ->visibleToPublic()
            ->with(['coverMedia', 'category', 'template'])
            ->withCount('media')
            ->latest('published_at')
            ->take(9)
            ->get();

        $categories = Category::query()
            ->withCount(['posts' => fn ($query) => $query->visibleToPublic()])
            ->orderBy('sort_order')
            ->get();

        return view('frontend.pages.home', compact('featured', 'latest', 'categories'));
    }
}
