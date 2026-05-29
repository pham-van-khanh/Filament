<?php

namespace Database\Seeders;

use App\Enums\MediaType;
use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Reaction;
use App\Models\SectionType;
use App\Models\Tag;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()
            ->where('email', 'admin@chuaminh.vn')
            ->firstOrFail();

        $category = Category::query()->updateOrCreate(
            ['slug' => 'du-lich'],
            [
                'name' => 'Du lịch',
                'description' => 'Những chuyến đi được lưu lại bằng hình ảnh.',
                'color' => '#0F4C81',
                'sort_order' => 1,
            ],
        );

        $tags = collect([
            ['sapa', 'Sapa', '#0F4C81'],
            ['lao-cai', 'Lào Cai', '#0F4C81'],
            ['fansipan', 'Fansipan', '#0F4C81'],
            ['ky-niem', 'Kỷ niệm', '#D95B8A'],
            ['chung-minh', 'Chúng mình', '#D95B8A'],
        ])->map(function (array $item) {
            [$slug, $name, $color] = $item;

            return Tag::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'color' => $color],
            );
        });

        // Demo media uses remote images; newly created video blocks accept uploaded files.
        $media = collect([
            ['memory-hero.jpg', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=85', 'Thung lũng trong sương sớm', 'Buổi sáng đầu tiên giữa núi và mây.', 1800, 1200],
            ['memory-rice.jpg', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=85', 'Ruộng bậc thang lúc hoàng hôn', 'Nắng rơi chậm trên những thửa ruộng bậc thang.', 1600, 1100],
            ['memory-fog.jpg', 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1600&q=85', 'Con đường mờ sương', 'Một con đường nhỏ, một lớp sương mỏng.', 1600, 1100],
            ['memory-note.jpg', 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=1600&q=85', 'Hoa và lời nhắn', 'Một lời nhắn nhỏ gửi lại cho ngày hôm đó.', 1600, 1100],
            ['memory-city.jpg', 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1600&q=85', 'Phố nhỏ sau mưa', 'Ánh đèn và con phố sau mưa.', 1600, 1100],
            ['memory-sakura.jpg', 'https://images.unsplash.com/photo-1522383225653-ed111181a951?auto=format&fit=crop&w=1600&q=85', 'Mùa hoa', 'Một mùa hoa nhẹ như lời hứa.', 1600, 1100],
            ['memory-lake.jpg', 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1600&q=85', 'Mặt hồ bình yên', 'Bình yên nằm lại bên mặt nước.', 1600, 1100],
            ['memory-trail.jpg', 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1600&q=85', 'Đường lên núi', 'Con dốc dẫn tới vùng trời rộng hơn.', 1600, 1100],
        ])->mapWithKeys(function (array $item) use ($admin): array {
            [$filename, $url, $alt, $caption, $width, $height] = $item;

            $record = Media::query()->updateOrCreate(
                ['filename' => $filename],
                [
                    'user_id' => $admin->id,
                    'disk' => 'public',
                    'type' => MediaType::Image,
                    'mime_type' => 'image/jpeg',
                    'original_name' => $filename,
                    'path' => 'seed/'.$filename,
                    'url' => $url,
                    'alt' => $alt,
                    'caption' => $caption,
                    'width' => $width,
                    'height' => $height,
                    'size' => 0,
                ],
            );

            return [$filename => $record];
        });

        $cover = $media['memory-hero.jpg'];
        $template = Template::query()->where('slug', 'mountain-forest')->firstOrFail();

        $post = Post::query()->updateOrCreate(
            ['slug' => 'mot-ngay-chung-minh-o-giua-may-troi'],
            [
                'user_id' => $admin->id,
                'template_id' => $template->id,
                'category_id' => $category->id,
                'title' => 'Một ngày chúng mình ở giữa mây trời',
                'excerpt' => 'Một album ảnh về Sapa, với nhiều cách xếp ảnh để những khoảnh khắc được kể bằng hình.',
                'cover_media_id' => $cover->id,
                'status' => PostStatus::Published,
                'visibility' => PostVisibility::Public,
                'published_at' => now(),
                'memory_date' => '2025-09-22',
                'location_name' => 'Sapa, Lào Cai',
                'mood' => 'núi và sương',
                'is_featured' => true,
                'seo_title' => 'Một ngày chúng mình ở giữa mây trời | chuaminh.vn',
                'seo_description' => 'Album ảnh lưu lại chuyến đi giữa núi, mây và những ngày thật yên.',
                'og_media_id' => $cover->id,
            ],
        );

        $post->detail()->updateOrCreate([], [
            'date_range' => '22 - 25 tháng 9, 2025',
            'music_enabled' => false,
            'music_url' => null,
            'music_title' => null,
            'music_artist' => null,
        ]);

        $post->tags()->sync($tags->pluck('id')->all());

        $galleryMedia = collect([
            $media['memory-hero.jpg'],
            $media['memory-rice.jpg'],
            $media['memory-fog.jpg'],
            $media['memory-note.jpg'],
            $media['memory-city.jpg'],
            $media['memory-sakura.jpg'],
            $media['memory-lake.jpg'],
            $media['memory-trail.jpg'],
        ]);

        $post->media()->sync(
            $galleryMedia->mapWithKeys(fn (Media $item, int $index): array => [
                $item->id => [
                    'role' => $index === 0 ? 'cover' : 'gallery',
                    'sort_order' => $index + 1,
                ],
            ])->all(),
        );

        $sectionTypes = SectionType::query()->pluck('id', 'slug');

        $post->sections()->delete();

        $sections = [
            [
                'type' => 'hero_image',
                'title' => 'Ảnh mở đầu',
                'variant' => 'memory_header',
                'media_id' => $cover->id,
                'headline' => 'Một ngày chúng mình ở giữa mây trời',
                'caption' => '22 - 25 tháng 9, 2025 · Sapa, Lào Cai',
                'height' => '540px',
            ],
            [
                'type' => 'stats',
                'title' => 'Những con số nhỏ',
                'variant' => 'mobile_row',
                'items' => [
                    ['value' => '4', 'label' => 'Ngày bên nhau'],
                    ['value' => '18', 'label' => 'Ảnh chụp'],
                    ['value' => '3', 'label' => 'Địa điểm'],
                    ['value' => '16°C', 'label' => 'Thời tiết'],
                ],
            ],
            [
                'type' => 'gallery_grid',
                'title' => 'Gallery mosaic',
                'variant' => 'mosaic',
                'layout' => 'mosaic',
                'items' => $galleryMedia->map(fn (Media $item): array => [
                    'media_id' => $item->id,
                    'caption' => $item->caption,
                ])->values()->all(),
            ],
            [
                'type' => 'gallery_grid',
                'title' => 'Những khung hình như postcard',
                'variant' => 'polaroid',
                'layout' => 'polaroid',
                'items' => $galleryMedia->slice(2, 6)->map(fn (Media $item): array => [
                    'media_id' => $item->id,
                    'caption' => $item->caption,
                ])->values()->all(),
            ],
            [
                'type' => 'gallery_slider',
                'title' => 'Lướt qua từng khoảnh khắc',
                'variant' => 'featured_moments',
                'layout' => 'featured_moments',
                'height' => '360px',
                'autoplay' => true,
                'items' => $galleryMedia->map(fn (Media $item): array => [
                    'media_id' => $item->id,
                    'caption' => $item->caption,
                ])->values()->all(),
            ],
            [
                'type' => 'single_image',
                'title' => 'Tấm ảnh muốn giữ thật lâu',
                'variant' => 'framed',
                'media_id' => $media['memory-rice.jpg']->id,
                'caption' => 'Chiều hôm đó, nắng rơi xuống rất chậm trên những thửa ruộng bậc thang.',
            ],
            [
                'type' => 'timeline',
                'title' => 'Hành trình của chúng mình',
                'variant' => 'vertical',
                'items' => [
                    [
                        'time_label' => 'Ngày 1 · 06:00',
                        'title' => 'Bắt đầu chuyến đi',
                        'body' => 'Rời thành phố khi trời còn dịu, mang theo máy ảnh và một playlist quen.',
                        'media_id' => $media['memory-hero.jpg']->id,
                    ],
                    [
                        'time_label' => 'Ngày 2 · 17:30',
                        'title' => 'Hoàng hôn trên núi',
                        'body' => 'Nắng xuống chậm trên ruộng bậc thang, đủ lâu để mình đứng lại ngắm nhìn.',
                        'media_id' => $media['memory-rice.jpg']->id,
                    ],
                    [
                        'time_label' => 'Ngày 3 · 08:15',
                        'title' => 'Đi qua màn sương',
                        'body' => 'Con đường nhỏ trong sương trở thành một trong những tấm ảnh muốn giữ nhất.',
                        'media_id' => $media['memory-fog.jpg']->id,
                    ],
                ],
            ],
            [
                'type' => 'quote',
                'title' => 'Một dòng để nhớ',
                'variant' => 'soft_card',
                'quote_text' => 'Sau này nếu có mệt, mình lại mở những tấm ảnh này ra để nhớ rằng đã từng có những ngày thật yên.',
                'quote_author' => 'Khánh · 23/09/2025',
            ],
        ];

        foreach ($sections as $index => $section) {
            $items = $section['items'] ?? [];
            unset($section['items']);

            $sectionModel = $post->sections()->create([
                ...$section,
                'section_type_id' => $sectionTypes[$section['type']] ?? null,
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);

            foreach ($items as $itemIndex => $item) {
                $sectionModel->items()->create([
                    ...$item,
                    'kind' => $section['type'],
                    'sort_order' => $itemIndex + 1,
                ]);
            }
        }

        Comment::query()->firstOrCreate(
            [
                'post_id' => $post->id,
                'name' => 'Thu Linh',
                'content' => 'Các bố cục ảnh nhìn rất thoáng và cảm xúc.',
            ],
            ['status' => 'approved'],
        );

        Comment::query()->firstOrCreate(
            [
                'post_id' => $post->id,
                'name' => 'Minh Nhật',
                'content' => 'Gallery mosaic hợp với chuyến đi này quá.',
            ],
            ['status' => 'approved'],
        );

        foreach (['love', 'like', 'wow'] as $reaction) {
            Reaction::query()->firstOrCreate(
                [
                    'post_id' => $post->id,
                    'session_id' => "demo-gallery-{$reaction}",
                    'reaction_type' => $reaction,
                ],
                ['ip_address' => '127.0.0.1'],
            );
        }

        PrivateMessage::query()->firstOrCreate(
            [
                'post_id' => $post->id,
                'name' => 'Người xem bí mật',
                'message' => 'Chúc hai bạn có thêm thật nhiều chuyến đi đẹp như thế này.',
            ],
            ['status' => 'unread'],
        );
    }
}
