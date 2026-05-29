<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\Rendering\MediaCollector;
use App\Services\Rendering\TemplateResolver;
use Illuminate\Contracts\View\View;

class UnlistedMemoryController extends Controller
{
    public function __invoke(string $token, TemplateResolver $templates, MediaCollector $media): View
    {
        $post = Post::query()->where('unlisted_token', $token)->firstOrFail();

        $this->authorize('view', $post);

        $post->load(['detail', 'template', 'coverMedia', 'category', 'tags', 'visibleSections.sectionType', 'visibleSections.items']);

        return view('frontend.posts.show', [
            'post' => $post,
            'templateView' => $templates->viewFor($post),
            'mediaById' => $media->forPost($post),
            'related' => collect(),
        ]);
    }
}
