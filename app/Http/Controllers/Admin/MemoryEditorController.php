<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\SectionType;
use App\Models\Template;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemoryEditorController extends Controller
{
    public function edit(Post $post): View
    {
        $post->load(['template', 'category', 'coverMedia', 'sections.sectionType', 'media']);

        return view('admin.memories.editor', [
            'post' => $post,
            'templates' => Template::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'media' => Media::query()->latest()->take(80)->get(),
            'sectionTypes' => SectionType::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:200', 'unique:posts,slug,'.$post->id],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'template_id' => ['required', 'exists:templates,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'cover_media_id' => ['nullable', 'exists:media,id'],
            'memory_date' => ['nullable', 'date'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published,hidden'],
            'visibility' => ['required', 'in:public,private,unlisted,password'],
            'sections_json' => ['required', 'json'],
        ]);

        $sections = collect(json_decode($data['sections_json'], true) ?: [])
            ->filter(fn (array $section) => filled($section['type'] ?? null))
            ->values();

        $post->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'] ?? null,
            'template_id' => $data['template_id'],
            'category_id' => $data['category_id'] ?? null,
            'cover_media_id' => $data['cover_media_id'] ?? null,
            'memory_date' => $data['memory_date'] ?? null,
            'location_name' => $data['location_name'] ?? null,
            'status' => PostStatus::from($data['status']),
            'visibility' => PostVisibility::from($data['visibility']),
            'published_at' => $data['status'] === 'published' ? ($post->published_at ?: now()) : $post->published_at,
            'settings' => array_replace_recursive($post->settings ?? [], [
                'date_range' => $request->string('date_range')->toString(),
                'music' => [
                    'enabled' => $request->boolean('music_enabled'),
                    'url' => $request->string('music_url')->toString(),
                    'title' => $request->string('music_title')->toString(),
                    'artist' => $request->string('music_artist')->toString(),
                ],
            ]),
        ]);

        $sectionTypeIds = SectionType::query()->pluck('id', 'slug');
        $post->sections()->delete();

        foreach ($sections as $index => $section) {
            $post->sections()->create([
                'section_type_id' => $sectionTypeIds[$section['type']] ?? null,
                'type' => $section['type'],
                'title' => $section['title'] ?? null,
                'subtitle' => $section['subtitle'] ?? null,
                'variant' => $section['variant'] ?? null,
                'data' => $section['data'] ?? [],
                'style' => $section['style'] ?? [],
                'settings' => $section['settings'] ?? [],
                'sort_order' => $index + 1,
                'is_visible' => (bool) ($section['is_visible'] ?? true),
            ]);
        }

        return redirect()
            ->route('admin.memories.editor', $post)
            ->with('status', 'Da luu memory.');
    }
}
