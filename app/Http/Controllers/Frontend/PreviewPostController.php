<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Services\Rendering\MediaCollector;
use App\Services\Rendering\TemplateResolver;
use Illuminate\Contracts\View\View;

class PreviewPostController
{
    public function __invoke(Post $post, TemplateResolver $templates, MediaCollector $media): View
    {
        $post->load(['template', 'coverMedia', 'category', 'tags', 'visibleSections.sectionType']);

        return view('frontend.posts.show', [
            'post' => $post,
            'templateView' => $templates->viewFor($post),
            'mediaById' => $media->forPost($post),
            'related' => collect(),
            'isPreview' => true,
        ]);
    }
}

