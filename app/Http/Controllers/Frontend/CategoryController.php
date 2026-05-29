<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController
{
    public function __invoke(Category $category): View
    {
        $posts = $category->posts()
            ->visibleToPublic()
            ->with(['detail', 'coverMedia', 'template', 'tags'])
            ->latest('memory_date')
            ->paginate(12);

        return view('frontend.pages.category', compact('category', 'posts'));
    }
}

