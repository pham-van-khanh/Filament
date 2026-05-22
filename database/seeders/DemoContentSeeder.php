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

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        $category = Category::query()->updateOrCreate(
            ['slug' => 'du-lich'],
            [
                'name' => 'Du lich',
                'description' => 'Nhom ky niem du lich, yeu thuong va nhung chuyen di.',
                'color' => '#0F4C81',
                'sort_order' => 1,
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        */

        $tags = collect([
            ['sapa', 'Sapa', '#0F4C81'],
            ['lao-cai', 'Lao Cai', '#0F4C81'],
            ['fansipan', 'Fansipan', '#0F4C81'],
            ['ky-niem', 'Ky niem', '#D95B8A'],
            ['chung-minh', 'Chung minh', '#D95B8A'],
        ])->map(function (array $item) {
            [$slug, $name, $color] = $item;

            return Tag::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'color' => $color,
                ],
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Media demo
        |--------------------------------------------------------------------------
        | Dung URL truc tiep de seed nhanh.
        | Sau nay co the thay bang file upload trong storage/app/public.
        */

        $media = collect([
            [
                'filename' => 'memory-hero.jpg',
                'url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=85',
                'alt' => 'Thung lung trong suong som',
                'caption' => 'Buoi sang dau tien chung minh thuc day giua nui va may.',
                'width' => 1800,
                'height' => 1200,
            ],
            [
                'filename' => 'memory-rice.jpg',
                'url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=85',
                'alt' => 'Ruong bac thang luc hoang hon',
                'caption' => 'Anh hoang hon vang tren nhung thua ruong bac thang.',
                'width' => 1600,
                'height' => 1100,
            ],
            [
                'filename' => 'memory-fog.jpg',
                'url' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1600&q=85',
                'alt' => 'Con duong mo suong',
                'caption' => 'Mot con duong nho, mot lop suong mong va hai dua minh.',
                'width' => 1600,
                'height' => 1100,
            ],
            [
                'filename' => 'memory-couple-note.jpg',
                'url' => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=1600&q=85',
                'alt' => 'Hoa va loi nhan',
                'caption' => 'Mot loi nhan nho gui lai cho ngay hom do.',
                'width' => 1600,
                'height' => 1100,
            ],
            [
                'filename' => 'memory-city.jpg',
                'url' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1600&q=85',
                'alt' => 'Pho co trong mua',
                'caption' => 'Anh den long va con pho sau mua.',
                'width' => 1600,
                'height' => 1100,
            ],
            [
                'filename' => 'memory-sakura.jpg',
                'url' => 'https://images.unsplash.com/photo-1522383225653-ed111181a951?auto=format&fit=crop&w=1600&q=85',
                'alt' => 'Hoa anh dao',
                'caption' => 'Mot mua hoa nhe nhu loi hua.',
                'width' => 1600,
                'height' => 1100,
            ],
        ])->mapWithKeys(function (array $item) use ($admin): array {
            $record = Media::query()->updateOrCreate(
                ['filename' => $item['filename']],
                [
                    'user_id' => $admin->id,
                    'disk' => 'public',
                    'type' => MediaType::Image,
                    'mime_type' => 'image/jpeg',
                    'original_name' => $item['filename'],
                    'path' => 'seed/'.$item['filename'],
                    'url' => $item['url'],
                    'alt' => $item['alt'],
                    'caption' => $item['caption'],
                    'width' => $item['width'],
                    'height' => $item['height'],
                    'size' => 0,
                    'metadata' => [
                        'source' => 'unsplash-demo-url',
                    ],
                ],
            );

            return [$item['filename'] => $record];
        });

        $cover = $media['memory-hero.jpg'];

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        | TemplateSeeder hien dang co slug mountain-forest, beach-sun,
        | city-night, nature-green, romantic-love, wedding-special,
        | birthday-pastel, daily-polaroid.
        */

        $template = Template::query()
            ->where('slug', 'mountain-forest')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Post
        |--------------------------------------------------------------------------
        */

        $post = Post::query()->updateOrCreate(
            ['slug' => 'mot-ngay-chung-minh-o-giua-may-troi'],
            [
                'user_id' => $admin->id,
                'template_id' => $template->id,
                'category_id' => $category->id,
                'title' => 'Một ngày chúng mình ở giữa mây trời',
                'excerpt' => 'Một bài viết demo đầy đủ section: hero, stats, rich text, ảnh đơn, ảnh kèm chữ, quote, gallery, slider, video, timeline, music và ending.',
                'content' => 'Có những chuyến đi không cần quá dài, chỉ cần đủ ảnh, đủ cảm xúc và đủ một người để cùng nhớ lại.',
                'cover_media_id' => $cover->id,
                'status' => PostStatus::Published,
                'visibility' => PostVisibility::Public,
                'published_at' => now(),
                'memory_date' => '2025-09-22',
                'memory_date_precision' => 'day',
                'location_name' => 'Sapa, Lao Cai',
                'mood' => 'nui va suong',
                'is_featured' => true,
                'seo_title' => 'Một ngày chúng mình ở giữa mây trời | chuaminh.vn',
                'seo_description' => 'Bài demo đầy đủ tất cả section cho website lưu giữ kỷ niệm.',
                'og_media_id' => $cover->id,
                'settings' => [
                    'date_range' => '22 - 25 tháng 9, 2025',
                    'theme_note' => 'Demo full sections',
                    'music' => [
                        'enabled' => true,
                        'autoplay' => true,
                        'loop' => true,
                        'title' => 'Our Memory Song',
                        'artist' => 'chuaminh.vn',
                        'url' => asset('demo/music/our-song.mp3'),
                        'src' => asset('demo/music/our-song.mp3'),
                    ],
                ],
            ],
        );

        $post->tags()->sync($tags->pluck('id')->all());

        /*
        |--------------------------------------------------------------------------
        | Post media gallery
        |--------------------------------------------------------------------------
        */

        $galleryMedia = collect([
            $media['memory-hero.jpg'],
            $media['memory-rice.jpg'],
            $media['memory-fog.jpg'],
            $media['memory-couple-note.jpg'],
            $media['memory-city.jpg'],
            $media['memory-sakura.jpg'],
        ]);

        $post->media()->sync(
            $galleryMedia->mapWithKeys(function (Media $item, int $index) {
                return [
                    $item->id => [
                        'role' => $index === 0 ? 'cover' : 'gallery',
                        'sort_order' => $index + 1,
                        'metadata' => json_encode([
                            'caption' => $item->caption,
                        ]),
                    ],
                ];
            })->all(),
        );

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        | Day la bai post co tat ca section trong SectionTypeSeeder:
        | hero_image, stats, rich_text, single_image, image_text, quote,
        | gallery_grid, gallery_slider, video_embed, music, timeline, ending.
        */

        $sectionTypes = SectionType::query()->pluck('id', 'slug');

        $post->sections()->delete();

        $sections = [
            [
                'type' => 'hero_image',
                'title' => 'Hero mở đầu',
                'variant' => 'memory_header',
                'data' => [
                    'media_id' => $cover->id,
                    'headline' => 'Một ngày chúng mình ở giữa mây trời',
                    'subheadline' => 'Có những chuyến đi chỉ cần nhìn lại ảnh thôi là thấy tim mình mềm đi một chút.',
                    'date_range' => '22 - 25 tháng 9, 2025',
                    'location' => 'Sapa, Lao Cai',
                    'tags' => ['Sapa', 'Lao Cai', 'Kỷ niệm', 'Chúng mình'],
                    'cta_label' => 'Xem kỷ niệm',
                    'scroll_hint' => 'Kéo xuống để xem tiếp',
                ],
                'style' => [
                    '--section-height' => '640px',
                    '--overlay-opacity' => '0.45',
                ],
                'settings' => [
                    'lightbox' => true,
                    'full_bleed' => true,
                ],
            ],
            [
                'type' => 'stats',
                'title' => 'Những con số nhỏ',
                'variant' => 'mobile_row',
                'data' => [
                    'items' => [
                        ['4', 'Ngày bên nhau'],
                        ['6', 'Khoảnh khắc chính'],
                        ['128', 'Tấm ảnh'],
                        ['16°', 'Trời se lạnh'],
                    ],
                ],
                'style' => [],
                'settings' => [],
            ],
            [
                'type' => 'rich_text',
                'title' => 'Lời mở đầu',
                'variant' => 'prose',
                'data' => [
                    'html' => '
                        <h2>Chuyến đi bắt đầu từ một buổi sáng rất nhẹ</h2>
                        <p>Chúng mình rời thành phố khi trời còn chưa sáng hẳn. Không có kế hoạch quá lớn, chỉ là vài bộ đồ ấm, một chiếc máy ảnh, một playlist quen thuộc và mong muốn được cùng nhau đi đâu đó thật xa.</p>
                        <p>Có những ngày không cần quá nhiều lời. Chỉ cần ngồi cạnh nhau, nhìn mây trôi qua cửa kính, rồi thỉnh thoảng quay sang cười vì biết người kia cũng đang thấy yên bình giống mình.</p>
                    ',
                ],
                'style' => [
                    '--prose-width' => '720px',
                ],
                'settings' => [],
            ],
            [
                'type' => 'single_image',
                'title' => 'Ảnh đơn nổi bật',
                'variant' => 'framed',
                'data' => [
                    'media_id' => $media['memory-rice.jpg']->id,
                    'caption' => 'Chiều hôm đó, nắng rơi xuống rất chậm trên những thửa ruộng bậc thang.',
                    'alt' => $media['memory-rice.jpg']->alt,
                ],
                'style' => [
                    '--image-radius' => '24px',
                ],
                'settings' => [
                    'lightbox' => true,
                ],
            ],
            [
                'type' => 'image_text',
                'title' => 'Ảnh và câu chuyện',
                'variant' => 'image_left_text_right',
                'data' => [
                    'media_id' => $media['memory-fog.jpg']->id,
                    'eyebrow' => 'Ngày thứ hai',
                    'heading' => 'Con đường nhỏ trong sương',
                    'body' => '
                        <p>Sáng hôm ấy sương phủ kín cả con đường. Hai đứa đi chậm hơn bình thường, không phải vì mệt, mà vì cảnh trước mắt đẹp đến mức không nỡ đi nhanh.</p>
                        <p>Có một đoạn em dừng lại rất lâu chỉ để nhìn mây trôi qua sườn núi. Anh đứng phía sau chụp một tấm ảnh, không gọi em quay lại, vì khoảnh khắc đó tự nhiên quá.</p>
                    ',
                    'caption' => 'Một đoạn đường nhỏ nhưng là một trong những đoạn nhớ nhất.',
                ],
                'style' => [
                    '--section-gap' => '32px',
                ],
                'settings' => [
                    'lightbox' => true,
                ],
            ],
            [
                'type' => 'quote',
                'title' => 'Một câu mình muốn giữ lại',
                'variant' => 'large_center',
                'data' => [
                    'quote' => 'Sau này nếu có mệt, mình lại mở những tấm ảnh này ra, để nhớ rằng đã từng có những ngày chúng mình bình yên đến thế.',
                    'author' => 'Khanh · 23/09/2025',
                ],
                'style' => [],
                'settings' => [],
            ],
            [
                'type' => 'gallery_grid',
                'title' => 'Gallery dạng lưới',
                'variant' => 'mosaic',
                'data' => [
                    'heading' => 'Những mảnh ghép của chuyến đi',
                    'description' => 'Một vài bức ảnh nhỏ ghép lại thành ký ức lớn.',
                    'media_ids' => $galleryMedia->pluck('id')->values()->all(),
                    'items' => $galleryMedia->map(function (Media $item) {
                        return [
                            'media_id' => $item->id,
                            'caption' => $item->caption,
                            'alt' => $item->alt,
                        ];
                    })->values()->all(),
                    'more_count' => 122,
                ],
                'style' => [],
                'settings' => [
                    'lightbox' => true,
                ],
            ],
            [
                'type' => 'gallery_slider',
                'title' => 'Gallery dạng slider',
                'variant' => 'featured_moments',
                'data' => [
                    'heading' => 'Khoảnh khắc nổi bật',
                    'description' => 'Vuốt nhẹ để xem lại từng lát cắt của chuyến đi.',
                    'autoplay' => true,
                    'interval' => 3500,
                    'slides' => $galleryMedia->map(function (Media $item) {
                        return [
                            'media_id' => $item->id,
                            'caption' => $item->caption,
                            'alt' => $item->alt,
                        ];
                    })->values()->all(),
                ],
                'style' => [
                    '--section-height' => '360px',
                ],
                'settings' => [
                    'lightbox' => true,
                    'show_dots' => true,
                    'show_arrows' => true,
                ],
            ],
            [
                'type' => 'video_embed',
                'title' => 'Video kỷ niệm',
                'variant' => 'cinematic_frame',
                'data' => [
                    'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'caption' => 'Một đoạn video ngắn để test layout video embed.',
                    'heading' => 'Một đoạn phim nhỏ',
                    'description' => 'Video này chỉ là demo. Khi dùng thật, bạn thay bằng link YouTube hoặc video của chính bạn.',
                ],
                'style' => [
                    '--video-radius' => '24px',
                ],
                'settings' => [],
            ],
            [
                'type' => 'timeline',
                'title' => 'Timeline chuyến đi',
                'variant' => 'image_cards',
                'data' => [
                    'heading' => 'Lịch trình của chúng mình',
                    'items' => [
                        [
                            'time' => 'Ngày 1 · 06:00',
                            'title' => 'Rời thành phố',
                            'body' => 'Hai đứa bắt đầu chuyến đi khi trời còn tối. Cà phê nóng, áo khoác mỏng và một playlist quen thuộc.',
                            'media_id' => $media['memory-hero.jpg']->id,
                        ],
                        [
                            'time' => 'Ngày 1 · 17:30',
                            'title' => 'Ngắm hoàng hôn',
                            'body' => 'Tụi mình đứng rất lâu ở một đoạn đường cao, nhìn nắng đổi màu trên ruộng bậc thang.',
                            'media_id' => $media['memory-rice.jpg']->id,
                        ],
                        [
                            'time' => 'Ngày 2 · 08:15',
                            'title' => 'Đi trong sương',
                            'body' => 'Sương phủ kín đường, mọi thứ chậm lại, chỉ còn tiếng bước chân và tiếng cười rất khẽ.',
                            'media_id' => $media['memory-fog.jpg']->id,
                        ],
                        [
                            'time' => 'Ngày 3 · 20:00',
                            'title' => 'Viết lại vài dòng',
                            'body' => 'Buổi tối hai đứa ngồi cạnh cửa sổ, chọn lại ảnh và ghi vài dòng để sau này còn nhớ.',
                            'media_id' => $media['memory-couple-note.jpg']->id,
                        ],
                    ],
                ],
                'style' => [],
                'settings' => [
                    'lightbox' => true,
                ],
            ],
            [
                'type' => 'music',
                'title' => 'Nhạc nền kỷ niệm',
                'variant' => 'floating_player',
                'data' => [
                    'enabled' => true,
                    'autoplay' => true,
                    'loop' => true,
                    'title' => 'Our Memory Song',
                    'artist' => 'chuaminh.vn',
                    'url' => asset('demo/music/our-song.mp3'),
                    'src' => asset('demo/music/our-song.mp3'),
                    'cover_media_id' => $cover->id,
                    'caption' => 'Bật một bài nhạc nhẹ để xem lại kỷ niệm này.',
                ],
                'style' => [
                    'position' => 'bottom',
                ],
                'settings' => [
                    'sticky' => false,
                    'show_controls' => true,
                ],
            ],
            [
                'type' => 'ending',
                'title' => 'Kết bài',
                'variant' => 'signature',
                'data' => [
                    'title' => 'Cảm ơn vì đã cùng anh đi qua ngày hôm đó',
                    'body' => '
                        <p>Chuyến đi kết thúc, nhưng mỗi lần mở lại những bức ảnh này, cảm giác như mình vẫn đang ở đó: giữa gió lạnh, mây trắng và những điều rất dịu dàng.</p>
                        <p>Hy vọng sau này chúng mình sẽ còn nhiều bài viết như thế này nữa.</p>
                    ',
                    'signature' => 'Khanh gửi Nhi',
                    'date' => '25/09/2025',
                    'button_label' => 'Xem thêm kỷ niệm',
                    'button_url' => '/',
                ],
                'style' => [],
                'settings' => [],
            ],
        ];

        foreach ($sections as $index => $section) {
            $post->sections()->create([
                'section_type_id' => $sectionTypes[$section['type']] ?? null,
                'type' => $section['type'],
                'title' => $section['title'],
                'variant' => $section['variant'] ?? null,
                'data' => $section['data'] ?? [],
                'style' => $section['style'] ?? [],
                'settings' => $section['settings'] ?? [],
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Comments, reactions, private messages
        |--------------------------------------------------------------------------
        */

        Comment::query()->firstOrCreate(
            [
                'post_id' => $post->id,
                'name' => 'Thu Linh',
                'content' => 'Bài này đầy đủ section quá, nhìn là biết có thể dùng làm mẫu để nhập data thật rồi.',
            ],
            [
                'status' => 'approved',
            ],
        );

        Comment::query()->firstOrCreate(
            [
                'post_id' => $post->id,
                'name' => 'Minh Nhật',
                'content' => 'Ảnh đẹp, bố cục ổn, đặc biệt phần timeline và music rất hợp với kiểu lưu kỷ niệm.',
            ],
            [
                'status' => 'approved',
            ],
        );

        foreach (['love', 'like', 'wow'] as $reaction) {
            Reaction::query()->firstOrCreate(
                [
                    'post_id' => $post->id,
                    'session_id' => "demo-full-section-{$reaction}",
                    'reaction_type' => $reaction,
                ],
                [
                    'ip_address' => '127.0.0.1',
                ],
            );
        }

        PrivateMessage::query()->firstOrCreate(
            [
                'post_id' => $post->id,
                'name' => 'Người xem bí mật',
                'message' => 'Chúc hai bạn luôn có thêm thật nhiều chuyến đi đẹp như thế này.',
            ],
            [
                'status' => 'unread',
            ],
        );
    }
}
