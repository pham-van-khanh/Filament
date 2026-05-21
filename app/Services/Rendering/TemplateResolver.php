<?php

namespace App\Services\Rendering;

use App\Models\Post;
use Illuminate\Support\Facades\View;

class TemplateResolver
{
    public function viewFor(Post $post): string
    {
        $slug = str($post->template?->slug ?? 'default')->kebab()->toString();
        $view = "frontend.templates.{$slug}";

        return View::exists($view) ? $view : 'frontend.templates.default';
    }
}

