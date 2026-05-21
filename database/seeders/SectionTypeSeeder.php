<?php

namespace Database\Seeders;

use App\Models\SectionType;
use Illuminate\Database\Seeder;

class SectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['Hero Block', 'hero_image', 'visual', ['media_id' => 'integer', 'headline' => 'string', 'date_range' => 'string', 'tags' => 'array'], ['memory_header', 'card_invitation', 'fullscreen_overlay']],
            ['Stats', 'stats', 'meta', ['items' => 'array'], ['mobile_row', 'compact']],
            ['Rich Text', 'rich_text', 'text', ['html' => 'html'], ['prose', 'caption_note']],
            ['Single Image', 'single_image', 'image', ['media_id' => 'integer', 'caption' => 'string'], ['centered', 'full_width', 'framed']],
            ['Image Text', 'image_text', 'image', ['media_id' => 'integer', 'body' => 'html'], ['image_left_text_right', 'text_left_image_right', 'overlapping_card']],
            ['Quote', 'quote', 'text', ['quote' => 'string', 'author' => 'string'], ['soft_card', 'large_center', 'side_note']],
            ['Gallery Grid', 'gallery_grid', 'gallery', ['media_ids' => 'array'], ['mosaic', 'grid_2_columns', 'featured_left']],
            ['Gallery Slider', 'gallery_slider', 'gallery', ['slides' => 'array'], ['featured_moments', 'minimal_slider', 'film_strip']],
            ['Video Embed', 'video_embed', 'video', ['url' => 'url', 'caption' => 'string'], ['clean_embed', 'cinematic_frame']],
            ['Music', 'music', 'audio', ['url' => 'url', 'title' => 'string', 'artist' => 'string'], ['floating_player']],
            ['Timeline', 'timeline', 'timeline', ['items' => 'array'], ['vertical', 'image_cards', 'compact']],
            ['Ending Section', 'ending', 'text', ['title' => 'string', 'body' => 'html'], ['soft_fade', 'signature', 'minimal']],
        ];

        foreach ($types as $index => [$name, $slug, $category, $fields, $variants]) {
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
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
