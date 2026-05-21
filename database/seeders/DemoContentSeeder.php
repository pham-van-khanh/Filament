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
        $admin = User::query()->where('email', 'admin@chuaminh.vn')->firstOrFail();

        $categories = collect([
            ['Du lich', 'du-lich', '#3B82C4'],
            ['Tinh yeu', 'tinh-yeu', '#D95B8A'],
            ['Dip dac biet', 'dip-dac-biet', '#A9773E'],
            ['Sinh nhat', 'sinh-nhat', '#F43F8C'],
            ['Ngay thuong', 'ngay-thuong', '#7A7167'],
            ['Noel', 'noel', '#C24141'],
            ['Am thuc', 'am-thuc', '#13866F'],
            ['Thien nhien', 'thien-nhien', '#3F7D4A'],
            ['Thanh pho', 'thanh-pho', '#111111'],
        ])->mapWithKeys(function (array $item, int $index): array {
            [$name, $slug, $color] = $item;

            return [
                $slug => Category::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'description' => "Nhom ky niem {$name}.",
                        'color' => $color,
                        'sort_order' => $index + 1,
                    ],
                ),
            ];
        });

        foreach ([
            ['lao-cai', 'Lao Cai', '#0F4C81'],
            ['fansipan', 'Fansipan', '#0F4C81'],
            ['cat-cat', 'Cat Cat', '#0F4C81'],
            ['valentine', 'Valentine', '#D95B8A'],
            ['sinh-nhat', 'Sinh nhat', '#F43F8C'],
            ['hoi-an', 'Hoi An', '#13866F'],
        ] as [$slug, $name, $color]) {
            Tag::query()->updateOrCreate(['slug' => $slug], ['name' => $name, 'color' => $color]);
        }

        $media = collect([
            ['sapa-hero.jpg', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=85', 'Thung lung Sapa trong suong som'],
            ['sapa-rice.jpg', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=85', 'Ruong bac thang luc hoang hon'],
            ['sapa-fog.jpg', 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1600&q=85', 'Con duong mo suong'],
            ['valentine-cover.jpg', 'https://images.unsplash.com/photo-1518199266791-5375a83190b7?auto=format&fit=crop&w=1600&q=85', 'Valentine dau tien'],
            ['rose-note.jpg', 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=1600&q=85', 'Hoa va loi chuc'],
            ['birthday-cake.jpg', 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=1600&q=85', 'Banh sinh nhat'],
            ['birthday-party.jpg', 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=1600&q=85', 'Bong bay sinh nhat'],
            ['japan-sakura.jpg', 'https://images.unsplash.com/photo-1522383225653-ed111181a951?auto=format&fit=crop&w=1600&q=85', 'Anh dao mua dau tien'],
            ['hoian-rain.jpg', 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1600&q=85', 'Hoi An mua mua'],
        ])->mapWithKeys(function (array $item) use ($admin): array {
            [$filename, $url, $caption] = $item;

            $record = Media::query()->updateOrCreate(
                ['filename' => $filename],
                [
                    'user_id' => $admin->id,
                    'disk' => 'public',
                    'type' => MediaType::Image,
                    'mime_type' => 'image/jpeg',
                    'original_name' => $filename,
                    'path' => "seed/{$filename}",
                    'url' => $url,
                    'alt' => $caption,
                    'caption' => $caption,
                    'width' => 1600,
                    'height' => 1100,
                    'size' => 0,
                    'metadata' => ['source' => 'unsplash-demo-url'],
                ],
            );

            return [$filename => $record];
        });

        $this->createMemory($admin, $categories, $media, [
            'title' => 'Sapa — Mua lua vang thang 9',
            'slug' => 'sapa-mua-lua-vang-thang-9',
            'excerpt' => 'Bon ngay nho trong suong, nui va nhung ruong bac thang vang nhu mot loi hen.',
            'category' => 'du-lich',
            'template' => 'mountain-forest',
            'cover' => 'sapa-hero.jpg',
            'date' => '2024-09-22',
            'date_range' => '22 - 25 thang 9, 2024',
            'location' => 'Sapa, Lao Cai',
            'tags' => ['Lao Cai', 'Fansipan', 'Cat Cat'],
            'mood' => 'nui va suong',
            'stats' => [['4', 'Ngay'], ['18', 'Anh'], ['16°', 'Thoi tiet'], ['3', 'Dia diem']],
            'slides' => ['sapa-hero.jpg', 'sapa-rice.jpg', 'sapa-fog.jpg'],
            'quote' => 'Buoi sang mo cua so ra thay may trang phu kin ca thung lung. Em cu dung do mai khong noi duoc gi.',
            'quote_author' => 'Nhi · 23/09/2024',
        ]);

        $this->createMemory($admin, $categories, $media, [
            'title' => 'Valentine dau tien cua chung minh',
            'slug' => 'valentine-dau-tien-cua-chung-minh',
            'excerpt' => 'Mot ngay hong rat mem, co hoa, co anh va co mot cau noi den gio van nho.',
            'category' => 'tinh-yeu',
            'template' => 'romantic-love',
            'cover' => 'valentine-cover.jpg',
            'date' => '2025-02-14',
            'date_range' => '14 thang 2, 2025',
            'location' => 'Sai Gon',
            'tags' => ['Valentine', 'Chung minh'],
            'mood' => 'lang man',
            'stats' => [['1', 'Ngay'], ['12', 'Anh'], ['84', 'Luot thich'], ['2', 'Nguoi']],
            'slides' => ['valentine-cover.jpg', 'rose-note.jpg', 'birthday-party.jpg'],
            'quote' => 'Khi ban nhan duoc chiec thiep nay, hay biet rang chung minh dang rat hanh phuc vi co nhau trong cuoc song nay.',
            'quote_author' => 'Khanh · 14/02/2025',
        ]);

        $this->createMemory($admin, $categories, $media, [
            'title' => 'Sinh nhat Nhi 22 tuoi',
            'slug' => 'sinh-nhat-nhi-22-tuoi',
            'excerpt' => 'Mot chiec sinh nhat pastel, nhieu anh va mot dieu uoc chi hai dua biet.',
            'category' => 'sinh-nhat',
            'template' => 'birthday-pastel',
            'cover' => 'birthday-cake.jpg',
            'date' => '2025-01-05',
            'date_range' => '05 thang 1, 2025',
            'location' => 'Nha minh',
            'tags' => ['Sinh nhat', 'Nhi 22'],
            'mood' => 'vui tuoi',
            'stats' => [['1', 'Ngay'], ['15', 'Anh'], ['22', 'Tuoi'], ['1', 'Dieu uoc']],
            'slides' => ['birthday-cake.jpg', 'birthday-party.jpg', 'rose-note.jpg'],
            'quote' => 'Chuc em tuoi moi van cuoi nhieu nhu luc thoi nen va van nam tay anh tren moi chuyen di.',
            'quote_author' => 'Khanh · 05/01/2025',
        ]);

        $this->createMemory($admin, $categories, $media, [
            'title' => 'Nhat Ban — Anh dao mua dau tien',
            'slug' => 'nhat-ban-anh-dao-mua-dau-tien',
            'excerpt' => 'Mua xuan dau tien di xa, va bong anh dao roi nhe nhu mot cau chao.',
            'category' => 'du-lich',
            'template' => 'daily-polaroid',
            'cover' => 'japan-sakura.jpg',
            'date' => '2025-04-14',
            'date_range' => '14 - 20/04/2025',
            'location' => 'Tokyo, Japan',
            'tags' => ['Du lich', 'Anh dao'],
            'mood' => 'nhe nhang',
            'stats' => [['7', 'Ngay'], ['18', 'Anh'], ['12°', 'Thoi tiet'], ['5', 'Dia diem']],
            'slides' => ['japan-sakura.jpg', 'rose-note.jpg', 'sapa-fog.jpg'],
            'quote' => 'Hoa roi tren vai ao, va minh biet minh se nho mua xuan nay rat lau.',
            'quote_author' => 'Nhi · 16/04/2025',
        ]);

        $this->createMemory($admin, $categories, $media, [
            'title' => 'Hoi An mua mua',
            'slug' => 'hoi-an-mua-mua',
            'excerpt' => 'Nhung chiec den long phan chieu duoi mat duong uot va mot buoi toi rat yen.',
            'category' => 'du-lich',
            'template' => 'beach-sun',
            'cover' => 'hoian-rain.jpg',
            'date' => '2024-12-01',
            'date_range' => '12/2024',
            'location' => 'Hoi An',
            'tags' => ['Hoi An', 'Mua'],
            'mood' => 'am ap',
            'stats' => [['2', 'Ngay'], ['9', 'Anh'], ['24°', 'Thoi tiet'], ['2', 'Dia diem']],
            'slides' => ['hoian-rain.jpg', 'sapa-rice.jpg', 'sapa-fog.jpg'],
            'quote' => 'Mua roi, den long sang len, va ca pho nho bong tro nen rat rieng.',
            'quote_author' => 'Khanh · 12/2024',
        ]);
    }

    protected function createMemory(User $admin, $categories, $media, array $payload): void
    {
        $template = Template::query()->where('slug', $payload['template'])->firstOrFail();
        $cover = $media[$payload['cover']];

        $post = Post::query()->updateOrCreate(
            ['slug' => $payload['slug']],
            [
                'user_id' => $admin->id,
                'template_id' => $template->id,
                'category_id' => $categories[$payload['category']]->id,
                'title' => $payload['title'],
                'excerpt' => $payload['excerpt'],
                'content' => $payload['quote'],
                'cover_media_id' => $cover->id,
                'status' => PostStatus::Published,
                'visibility' => PostVisibility::Public,
                'published_at' => now(),
                'memory_date' => $payload['date'],
                'memory_date_precision' => 'day',
                'location_name' => $payload['location'],
                'mood' => $payload['mood'],
                'is_featured' => $payload['slug'] === 'sapa-mua-lua-vang-thang-9',
                'seo_title' => $payload['title'].' | chuaminh.vn',
                'seo_description' => $payload['excerpt'],
                'og_media_id' => $cover->id,
                'settings' => [
                    'date_range' => $payload['date_range'],
                    'music' => [
                        'enabled' => true,
                        'title' => 'Our Song',
                        'artist' => 'chuaminh.vn',
                    ],
                ],
            ],
        );

        $tagIds = collect($payload['tags'])
            ->map(fn (string $tag) => Tag::query()->firstOrCreate(
                ['slug' => str($tag)->slug()->toString()],
                ['name' => $tag, 'color' => $categories[$payload['category']]->color],
            )->id);
        $post->tags()->sync($tagIds);

        $slideMedia = collect($payload['slides'])
            ->filter(fn (string $filename) => isset($media[$filename]))
            ->map(fn (string $filename) => $media[$filename]);

        $post->media()->sync(
            $slideMedia->mapWithKeys(fn (Media $item, int $index) => [
                $item->id => [
                    'role' => $index === 0 ? 'cover' : 'gallery',
                    'sort_order' => $index + 1,
                    'metadata' => json_encode(['caption' => $item->caption]),
                ],
            ])->all(),
        );

        $sectionTypes = SectionType::query()->pluck('id', 'slug');
        $post->sections()->delete();

        $sections = [
            [
                'type' => 'hero_image',
                'title' => 'Hero block',
                'variant' => 'memory_header',
                'data' => [
                    'media_id' => $cover->id,
                    'headline' => $payload['title'],
                    'date_range' => $payload['date_range'],
                    'location' => $payload['location'],
                    'tags' => $payload['tags'],
                ],
                'style' => ['--section-height' => '390px'],
            ],
            [
                'type' => 'stats',
                'title' => 'Thong ke',
                'variant' => 'mobile_row',
                'data' => ['items' => $payload['stats']],
                'style' => [],
            ],
            [
                'type' => 'gallery_slider',
                'title' => 'Khoanh khac noi bat',
                'variant' => 'featured_moments',
                'data' => [
                    'autoplay' => false,
                    'slides' => $slideMedia->map(fn (Media $item) => [
                        'media_id' => $item->id,
                        'caption' => $item->caption,
                    ])->values()->all(),
                ],
                'style' => ['--section-height' => '240px'],
            ],
            [
                'type' => 'quote',
                'title' => 'Loi nho',
                'variant' => 'soft_card',
                'data' => [
                    'quote' => $payload['quote'],
                    'author' => $payload['quote_author'],
                ],
                'style' => [],
            ],
            [
                'type' => 'gallery_grid',
                'title' => 'Tat ca anh',
                'variant' => 'mosaic',
                'data' => [
                    'media_ids' => $slideMedia->pluck('id')->values()->all(),
                    'more_count' => max(0, (int) preg_replace('/\D+/', '', $payload['stats'][1][0]) - $slideMedia->count()),
                ],
                'style' => [],
            ],
        ];

        foreach ($sections as $index => $section) {
            $post->sections()->create([
                'section_type_id' => $sectionTypes[$section['type']] ?? null,
                'type' => $section['type'],
                'title' => $section['title'],
                'variant' => $section['variant'],
                'data' => $section['data'],
                'style' => $section['style'],
                'settings' => ['lightbox' => true],
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);
        }

        Comment::query()->firstOrCreate(
            ['post_id' => $post->id, 'name' => 'Thu Linh', 'content' => 'Anh dep qua! Nhin la muon di cung hai ban lien.'],
            ['status' => 'approved'],
        );

        Comment::query()->firstOrCreate(
            ['post_id' => $post->id, 'name' => 'Minh Nhat', 'content' => 'Cap doi nay di dau cung co anh xinh qua.'],
            ['status' => 'approved'],
        );

        foreach (['love', 'like', 'wow'] as $reaction) {
            Reaction::query()->firstOrCreate(
                ['post_id' => $post->id, 'session_id' => "seed-{$reaction}-{$post->slug}", 'reaction_type' => $reaction],
                ['ip_address' => '127.0.0.1'],
            );
        }

        PrivateMessage::query()->firstOrCreate(
            ['post_id' => $post->id, 'name' => 'Nguoi xem bi mat', 'message' => 'Chuc hai ban luon giu duoc nhung khoanh khac dep nhu the nay.'],
            ['status' => 'unread'],
        );
    }
}
