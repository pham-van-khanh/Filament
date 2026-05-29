<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MediaType;
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
use Illuminate\Validation\ValidationException;

class MemoryEditorController extends Controller
{
    public function edit(Post $post): View
    {
        $post->load(['detail', 'template', 'category', 'coverMedia', 'sections.sectionType', 'sections.items', 'media']);
        $post->sections->each->setRelation('post', $post);

        $latestMedia = Media::query()->latest()->take(120)->get();
        $usedMediaIds = $post->sections
            ->flatMap(fn ($section) => [
                $section->media_id,
                ...$section->items->pluck('media_id')->all(),
            ])
            ->push($post->cover_media_id)
            ->filter()
            ->unique()
            ->values();

        $usedMedia = $usedMediaIds->isEmpty()
            ? collect()
            : Media::query()->whereIn('id', $usedMediaIds)->get();

        $sectionTypes = SectionType::query()->where('is_active', true)->orderBy('sort_order')->get();
        $addableSectionTypes = $sectionTypes
            ->whereIn('slug', [
                'hero_image',
                'stats',
                'single_image',
                'gallery_grid',
                'gallery_slider',
                'video_embed',
                'quote',
                'music',
                'timeline',
            ])
            ->values();

        return view('admin.memories.editor', [
            'post' => $post,
            'templates' => Template::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'media' => $usedMedia->merge($latestMedia)->unique('id')->values(),
            'sectionTypes' => $sectionTypes,
            'addableSectionTypes' => $addableSectionTypes,
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
            'date_range' => ['nullable', 'string', 'max:120'],
            'music_enabled' => ['nullable', 'boolean'],
            'music_url' => ['nullable', 'string', 'max:2048'],
            'music_title' => ['nullable', 'string', 'max:180'],
            'music_artist' => ['nullable', 'string', 'max:180'],
            'sections' => ['nullable', 'array'],
            'sections.*.type' => ['required_with:sections', 'string', 'max:80'],
            'sections.*.title' => ['nullable', 'string', 'max:180'],
            'sections.*.subtitle' => ['nullable', 'string', 'max:180'],
            'sections.*.variant' => ['nullable', 'string', 'max:120'],
            'sections.*.media_id' => ['nullable', 'integer', 'exists:media,id'],
            'sections.*.headline' => ['nullable', 'string', 'max:255'],
            'sections.*.body' => ['nullable', 'string'],
            'sections.*.quote_text' => ['nullable', 'string'],
            'sections.*.quote_author' => ['nullable', 'string', 'max:180'],
            'sections.*.caption' => ['nullable', 'string', 'max:1000'],
            'sections.*.url' => ['nullable', 'string', 'max:2048'],
            'sections.*.height' => ['nullable', 'string', 'max:40'],
            'sections.*.layout' => ['nullable', 'string', 'max:120'],
            'sections.*.autoplay' => ['nullable', 'boolean'],
            'sections.*.is_visible' => ['nullable', 'boolean'],
            'sections.*.items' => ['nullable', 'array'],
            'sections.*.items.*.media_id' => ['nullable', 'integer', 'exists:media,id'],
            'sections.*.items.*.title' => ['nullable', 'string', 'max:180'],
            'sections.*.items.*.subtitle' => ['nullable', 'string', 'max:180'],
            'sections.*.items.*.value' => ['nullable', 'string', 'max:80'],
            'sections.*.items.*.label' => ['nullable', 'string', 'max:120'],
            'sections.*.items.*.time_label' => ['nullable', 'string', 'max:120'],
            'sections.*.items.*.body' => ['nullable', 'string'],
            'sections.*.items.*.caption' => ['nullable', 'string', 'max:1000'],
            'sections.*.items.*.url' => ['nullable', 'string', 'max:2048'],
        ]);

        $sections = collect($data['sections'] ?? [])
            ->filter(fn (array $section) => filled($section['type'] ?? null))
            ->values();

        foreach ($sections->where('type', 'video_embed') as $section) {
            $videoId = $section['media_id'] ?? null;

            if ($videoId && ! Media::query()->whereKey($videoId)->where('type', MediaType::Video->value)->exists()) {
                throw ValidationException::withMessages([
                    'sections' => 'Video block chi chap nhan tep video da upload.',
                ]);
            }
        }

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
        ]);

        $post->detail()->updateOrCreate([], [
            'date_range' => $data['date_range'] ?? null,
            'music_enabled' => $request->boolean('music_enabled'),
            'music_url' => $data['music_url'] ?? null,
            'music_title' => $data['music_title'] ?? null,
            'music_artist' => $data['music_artist'] ?? null,
        ]);

        $sectionTypeIds = SectionType::query()->pluck('id', 'slug');
        $post->sections()->delete();

        foreach ($sections as $index => $section) {
            $sectionModel = $post->sections()->create([
                'section_type_id' => $sectionTypeIds[$section['type']] ?? null,
                'type' => $section['type'],
                'title' => $section['title'] ?? null,
                'subtitle' => $section['subtitle'] ?? null,
                'variant' => $section['variant'] ?? null,
                'media_id' => $section['media_id'] ?? null,
                'headline' => $section['headline'] ?? null,
                'body' => $this->normaliseSectionBody($section['type'], $section['body'] ?? null),
                'quote_text' => $section['quote_text'] ?? null,
                'quote_author' => $section['quote_author'] ?? null,
                'caption' => $section['caption'] ?? null,
                'url' => $section['type'] === 'video_embed' ? null : ($section['url'] ?? null),
                'height' => $section['height'] ?? null,
                'layout' => $section['layout'] ?? null,
                'autoplay' => (bool) ($section['autoplay'] ?? false),
                'sort_order' => $index + 1,
                'is_visible' => (bool) ($section['is_visible'] ?? true),
            ]);

            foreach (collect($section['items'] ?? [])->values() as $itemIndex => $item) {
                if (! $this->hasSectionItemContent($item)) {
                    continue;
                }

                $sectionModel->items()->create([
                    'media_id' => $item['media_id'] ?? null,
                    'kind' => $section['type'],
                    'title' => $item['title'] ?? null,
                    'subtitle' => $item['subtitle'] ?? null,
                    'value' => $item['value'] ?? null,
                    'label' => $item['label'] ?? null,
                    'time_label' => $item['time_label'] ?? null,
                    'body' => $item['body'] ?? null,
                    'caption' => $item['caption'] ?? null,
                    'url' => $item['url'] ?? null,
                    'sort_order' => $itemIndex + 1,
                ]);
            }
        }

        $mediaIds = $sections
            ->flatMap(fn (array $section): array => $this->collectTypedMediaIds($section))
            ->when($post->cover_media_id, fn ($ids) => $ids->push($post->cover_media_id))
            ->filter()
            ->unique()
            ->values();

        $post->media()->sync(
            $mediaIds->mapWithKeys(fn (int $mediaId, int $index): array => [
                $mediaId => [
                    'role' => $mediaId === $post->cover_media_id ? 'cover' : 'gallery',
                    'sort_order' => $index + 1,
                ],
            ])->all(),
        );

        return redirect()
            ->route('admin.memories.editor', $post)
            ->with('status', 'Da luu memory.');
    }

    protected function collectTypedMediaIds(array $section): array
    {
        $ids = [];

        if (is_numeric($section['media_id'] ?? null)) {
            $ids[] = (int) $section['media_id'];
        }

        foreach ($section['items'] ?? [] as $item) {
            if (is_numeric($item['media_id'] ?? null)) {
                $ids[] = (int) $item['media_id'];
            }
        }

        return $ids;
    }

    protected function hasSectionItemContent(array $item): bool
    {
        return collect($item)
            ->except(['sort_order'])
            ->contains(fn ($value) => filled($value));
    }

    protected function normaliseSectionBody(string $type, ?string $body): ?string
    {
        if (! filled($body) || ! in_array($type, ['rich_text', 'image_text', 'ending'], true)) {
            return $body;
        }

        if (str_contains($body, '<')) {
            return $body;
        }

        return collect(preg_split('/\R{2,}/', trim($body)) ?: [])
            ->map(fn (string $paragraph): string => '<p>'.e(trim($paragraph)).'</p>')
            ->implode("\n");
    }

}
