<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['Nui & rung', 'mountain-forest', 'Template du lich', '#0F4C81', '#EAF4FF', '#8BBCE8', '#ffffff', 'Playfair Display', true],
            ['Bien & nang', 'beach-sun', 'Template du lich', '#45A3C7', '#BDE7F6', '#F6C66A', '#103949', 'Playfair Display', false],
            ['Thanh pho dem', 'city-night', 'Template du lich', '#111111', '#1F1F1F', '#444444', '#ffffff', 'Playfair Display', false],
            ['Thien nhien', 'nature-green', 'Template du lich', '#3F7D4A', '#DFF2D8', '#8BCB77', '#13351b', 'Playfair Display', false],
            ['Lang man', 'romantic-love', 'Template khac', '#B55378', '#FCE8F0', '#E88AAF', '#681733', 'Cormorant Garamond', false],
            ['Cuoi / dac biet', 'wedding-special', 'Template khac', '#681733', '#F8DDE8', '#D97A9A', '#ffffff', 'Cormorant Garamond', false],
            ['Sinh nhat pastel', 'birthday-pastel', 'Template khac', '#F43F8C', '#FDE68A', '#F97316', '#38111f', 'Playfair Display', false],
            ['Ngay thuong polaroid', 'daily-polaroid', 'Template khac', '#F5F1E8', '#FFFFFF', '#D7CEC2', '#333333', 'Playfair Display', false],
        ];

        foreach ($templates as $index => [$name, $slug, $category, $primary, $surface, $accent, $text, $heading, $default]) {
            Template::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => match ($slug) {
                        'mountain-forest' => 'Navy, misty and cinematic for Sapa, Ha Giang, trekking and cool mountain trips.',
                        'beach-sun' => 'Airy blue and warm sun blocks for sea, summer and island memories.',
                        'city-night' => 'Dark cinematic panels for late-night city photos and neon moods.',
                        'nature-green' => 'Fresh green visual rhythm for gardens, flowers and quiet outdoor days.',
                        'romantic-love' => 'Soft pink and cream for couple albums and intimate notes.',
                        'wedding-special' => 'Invitation-like special moment template with names, wishes and warm comments.',
                        'birthday-pastel' => 'Joyful pastel birthday layout with playful blocks and highlight cards.',
                        default => 'Minimal polaroid-style memory template for everyday fragments.',
                    },
                    'category' => $category,
                    'mood' => str($slug)->replace('-', ' ')->toString(),
                    'is_active' => true,
                    'is_default' => $default,
                    'sort_order' => $index + 1,
                    'design_tokens' => [
                        'colors' => [
                            'background' => $surface,
                            'surface' => '#ffffff',
                            'text' => $text,
                            'muted' => in_array($slug, ['mountain-forest', 'city-night', 'wedding-special'], true) ? '#a9c5df' : '#6d625c',
                            'accent' => $accent,
                            'primary' => $primary,
                        ],
                        'typography' => [
                            'heading_font' => $heading,
                            'body_font' => 'Inter',
                        ],
                        'spacing' => [
                            'section_gap' => '56px',
                            'content_width' => '980px',
                            'prose_width' => '720px',
                        ],
                        'radius' => [
                            'card' => '18px',
                            'image' => '16px',
                        ],
                    ],
                    'layout_config' => [
                        'hero' => [
                            'type' => 'mobile_memory_cover',
                            'title_position' => 'bottom',
                            'overlay' => 'soft_gradient',
                        ],
                        'sections' => [
                            'default_width' => 'mobile-first',
                            'allow_full_bleed' => true,
                        ],
                    ],
                    'supported_section_types' => [
                        'hero_image',
                        'stats',
                        'gallery_slider',
                        'quote',
                        'gallery_grid',
                        'single_image',
                        'video_embed',
                        'music',
                        'timeline',
                    ],
                    'settings' => [
                        'editor_group' => str_contains($category, 'du lich') ? 'travel' : 'other',
                    ],
                ],
            );
        }
    }
}
