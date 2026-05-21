<?php

namespace App\Services\Rendering;

use App\Models\Post;
use App\Models\PostSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class SectionRenderer
{
    public function __construct(
        protected DesignTokenMerger $tokens,
    ) {}

    public function render(PostSection $section, Post $post, ?Collection $mediaById = null): string
    {
        $type = $section->component_type;
        $variant = $section->component_variant;

        $views = array_filter([
            $variant ? "frontend.sections.variants.{$type}.{$variant}" : null,
            "frontend.sections.{$type}",
            'frontend.sections.unknown-section',
        ]);

        $view = collect($views)->first(fn (string $candidate) => View::exists($candidate));

        return view($view, [
            'post' => $post,
            'section' => $section,
            'data' => $section->data ?? [],
            'style' => $this->tokens->sectionStyle($post, $section),
            'tokens' => $post->template?->design_tokens ?? [],
            'mediaById' => $mediaById ?? collect(),
        ])->render();
    }
}

