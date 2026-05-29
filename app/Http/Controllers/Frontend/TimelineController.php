<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class TimelineController
{
    public function __invoke(): View
    {
        $groups = Post::query()
            ->visibleToPublic()
            ->with(['detail', 'coverMedia', 'category'])
            ->whereNotNull('memory_date')
            ->latest('memory_date')
            ->take(120)
            ->get()
            ->groupBy(fn (Post $post) => $post->memory_date?->format('Y') ?? 'Unknown');

        return view('frontend.pages.timeline', compact('groups'));
    }
}

