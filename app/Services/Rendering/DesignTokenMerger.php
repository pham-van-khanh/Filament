<?php

namespace App\Services\Rendering;

use App\Models\Post;
use App\Models\PostSection;

class DesignTokenMerger
{
    public function sectionStyle(Post $post, PostSection $section): array
    {
        return array_replace_recursive(
            $post->template?->design_tokens['section_defaults'] ?? [],
            $section->sectionType?->default_style_schema ?? [],
            $section->style ?? [],
        );
    }

    public function cssVariables(Post $post): array
    {
        $tokens = $post->template?->design_tokens ?? [];

        return [
            '--memory-bg' => data_get($tokens, 'colors.background', '#ffffff'),
            '--memory-surface' => data_get($tokens, 'colors.surface', '#ffffff'),
            '--memory-text' => data_get($tokens, 'colors.text', '#171717'),
            '--memory-muted' => data_get($tokens, 'colors.muted', '#737373'),
            '--memory-accent' => data_get($tokens, 'colors.accent', '#4f8cff'),
            '--memory-heading-font' => '"'.data_get($tokens, 'typography.heading_font', 'Inter').'", serif',
            '--memory-body-font' => '"'.data_get($tokens, 'typography.body_font', 'Inter').'", sans-serif',
            '--memory-section-gap' => data_get($tokens, 'spacing.section_gap', '72px'),
            '--memory-content-width' => data_get($tokens, 'spacing.content_width', '1120px'),
            '--memory-prose-width' => data_get($tokens, 'spacing.prose_width', '760px'),
            '--memory-card-radius' => data_get($tokens, 'radius.card', '16px'),
            '--memory-image-radius' => data_get($tokens, 'radius.image', '16px'),
        ];
    }

    public function inlineCssVariables(Post $post): string
    {
        return collect($this->cssVariables($post))
            ->map(fn ($value, $key) => "{$key}: {$value}")
            ->implode('; ');
    }
}

