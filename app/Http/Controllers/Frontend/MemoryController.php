<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\PostVisibility;
use App\Models\Post;
use App\Services\Rendering\MediaCollector;
use App\Services\Rendering\TemplateResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MemoryController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->visibleToPublic()
            ->with(['detail', 'coverMedia', 'category', 'template', 'tags'])
            ->withCount('media')
            ->byCategory($request->string('category')->toString() ?: null)
            ->byTag($request->string('tag')->toString() ?: null)
            ->byTemplate($request->string('template')->toString() ?: null)
            ->search($request->string('q')->toString() ?: null)
            ->when($request->string('mood')->toString(), fn ($query, $mood) => $query->where('mood', $mood))
            ->when($request->string('year')->toString(), fn ($query, $year) => $query->whereYear('memory_date', $year))
            ->when(
                $request->string('sort')->toString() === 'oldest',
                fn ($query) => $query->oldest('memory_date'),
                fn ($query) => $query->latest('memory_date')->latest('published_at'),
            )
            ->paginate(12)
            ->withQueryString();

        return view('frontend.pages.memories', compact('posts'));
    }

    public function show(Post $post, TemplateResolver $templates, MediaCollector $media): View
    {
        if ($post->visibility === PostVisibility::Password && ! session()->has("post_password_{$post->id}")) {
            return view('frontend.pages.password', compact('post'));
        }

        $this->authorize('view', $post);

        $post->load([
            'template',
            'detail',
            'coverMedia',
            'category',
            'tags',
            'visibleSections.sectionType',
            'visibleSections.items',
            'approvedComments',
        ]);
        $post->loadCount(['approvedComments', 'reactions']);

        $templateView = $templates->viewFor($post);
        $mediaById = $media->forPost($post);
        $related = Post::query()
            ->visibleToPublic()
            ->whereKeyNot($post->id)
            ->with(['detail', 'coverMedia', 'category'])
            ->when($post->category_id, fn ($query) => $query->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.posts.show', compact('post', 'templateView', 'mediaById', 'related'));
    }
}
