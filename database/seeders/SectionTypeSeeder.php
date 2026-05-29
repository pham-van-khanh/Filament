<?php

namespace Database\Seeders;

use App\Models\SectionType;
use Illuminate\Database\Seeder;

class SectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['Hero Block', 'hero_image', 'visual', ['media_id' => 'integer', 'headline' => 'string'], ['memory_header'], true],
            ['Stats', 'stats', 'meta', ['items' => 'array'], ['mobile_row'], true],
            ['Single Image', 'single_image', 'image', ['media_id' => 'integer', 'caption' => 'string'], ['centered', 'full_width', 'framed'], true],
            ['Gallery Grid', 'gallery_grid', 'gallery', ['items' => 'array'], ['mosaic', 'grid_2', 'grid_3', 'featured_left', 'masonry', 'film_strip', 'polaroid'], true],
            ['Gallery Slider', 'gallery_slider', 'gallery', ['items' => 'array'], ['featured_moments', 'minimal_slider'], true],
            ['Video Upload', 'video_embed', 'video', ['media_id' => 'integer', 'caption' => 'string'], ['clean_video', 'vertical'], true],
            ['Quote', 'quote', 'text', ['quote' => 'string', 'author' => 'string'], ['soft_card', 'large_center'], true],
            ['Music', 'music', 'audio', ['url' => 'url', 'title' => 'string', 'artist' => 'string'], ['floating_player'], true],
            ['Rich Text', 'rich_text', 'legacy', ['html' => 'html'], ['prose'], false],
            ['Image Text', 'image_text', 'legacy', ['media_id' => 'integer', 'body' => 'html'], ['image_left_text_right'], false],
            ['Timeline', 'timeline', 'story', ['items' => 'array'], ['vertical'], true],
            ['Ending Section', 'ending', 'legacy', ['title' => 'string', 'body' => 'html'], ['minimal'], false],
        ];

        foreach ($types as $index => [$name, $slug, $category, $fields, $variants, $isActive]) {
            SectionType::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'category' => $category,
                    'description' => "MVP section type for {$name}.",
                    'icon' => 'heroicon-o-squares-2x2',
                    'default_data_schema' => [
                        'fields' => collect($fields)->map(fn ($type, $key) => [
                            'type' => $type,
                            'required' => in_array($key, ['media_id', 'html', 'quote', 'slides', 'items'], true),
                        ])->all(),
                    ],
                    'default_style_schema' => [
                        'background_color' => null,
                        'text_color' => null,
                        'spacing_y' => 'var(--memory-section-gap)',
                    ],
                    'available_variants' => collect($variants)->map(fn ($variant) => [
                        'slug' => $variant,
                        'label' => str($variant)->replace('_', ' ')->title()->toString(),
                    ])->all(),
                    'supported_templates' => null,
                    'is_active' => $isActive,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
