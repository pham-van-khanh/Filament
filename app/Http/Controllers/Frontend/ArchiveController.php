<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class ArchiveController
{
    public function __invoke(): View
    {
        $groups = Post::query()
            ->visibleToPublic()
            ->with('category')
            ->latest('memory_date')
            ->get()
            ->groupBy(fn (Post $post) => $post->memory_date?->format('Y') ?? 'Unknown');

        return view('frontend.pages.archive', compact('groups'));
    }
}

