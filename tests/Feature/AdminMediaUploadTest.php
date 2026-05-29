<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_tests_use_an_in_memory_database(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
    }

    public function test_admin_can_upload_media_from_editor(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->create('memory.jpg', 32, 'image/jpeg');

        $response = $this
            ->actingAs($admin)
            ->postJson(route('admin.media.upload'), [
                'file' => $file,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('media.name', 'memory.jpg')
            ->assertJsonPath('media.type', 'image');

        $media = Media::query()->firstOrFail();

        $this->assertSame($admin->id, $media->user_id);
        $this->assertSame('public', $media->disk);
        $this->assertSame('image/jpeg', $media->mime_type);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_guest_cannot_upload_media_from_editor(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('memory.jpg', 32, 'image/jpeg');

        $this->postJson(route('admin.media.upload'), [
            'file' => $file,
        ])->assertUnauthorized();

        $this->assertDatabaseCount('media', 0);
    }

    public function test_admin_editor_page_loads_upload_ui(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'admin@chuaminh.vn')->firstOrFail();
        $post = Post::query()->firstOrFail();

        $this
            ->actingAs($admin)
            ->get(route('admin.memories.editor', $post))
            ->assertOk()
            ->assertSee('mediaUploadInput', false)
            ->assertSee('mediaUploadUrl', false)
            ->assertSee('videoUploadInput', false)
            ->assertSee('data-add-block="timeline"', false)
            ->assertSee('data-block-panel="timeline"', false)
            ->assertDontSee('Link YouTube');
    }

    public function test_admin_can_save_typed_editor_sections(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'admin@chuaminh.vn')->firstOrFail();
        $post = Post::query()->with(['template', 'category', 'coverMedia'])->firstOrFail();
        $media = Media::query()->where('type', 'image')->take(2)->get();

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.memories.editor.update', $post), [
                'title' => 'Typed editor saved',
                'slug' => $post->slug,
                'excerpt' => 'Saved without raw JSON.',
                'template_id' => $post->template_id,
                'category_id' => $post->category_id,
                'cover_media_id' => $post->cover_media_id,
                'memory_date' => optional($post->memory_date)->format('Y-m-d'),
                'date_range' => '22 - 25/09/2025',
                'location_name' => 'Sapa',
                'status' => 'draft',
                'visibility' => 'public',
                'music_enabled' => '1',
                'music_url' => 'https://example.com/song.mp3',
                'music_title' => 'Our Song',
                'music_artist' => 'chuaminh.vn',
                'sections' => [
                    [
                        'type' => 'gallery_grid',
                        'title' => 'Gallery typed',
                        'is_visible' => '1',
                        'items' => [
                            [
                                'media_id' => $media[0]->id,
                                'caption' => 'First image',
                            ],
                            [
                                'media_id' => $media[1]->id,
                                'caption' => 'Second image',
                            ],
                        ],
                    ],
                    [
                        'type' => 'quote',
                        'title' => 'Quote typed',
                        'quote_text' => 'Mot cau nho.',
                        'quote_author' => 'Khanh',
                        'is_visible' => '1',
                    ],
                    [
                        'type' => 'timeline',
                        'title' => 'Timeline typed',
                        'is_visible' => '1',
                        'items' => [
                            [
                                'time_label' => 'Ngay 1 - 06:00',
                                'title' => 'Bat dau',
                                'body' => 'Mot moc ky niem.',
                                'media_id' => $media[1]->id,
                            ],
                        ],
                    ],
                ],
            ]);

        $response->assertRedirect(route('admin.memories.editor', $post));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Typed editor saved',
        ]);

        $this->assertDatabaseHas('post_details', [
            'post_id' => $post->id,
            'date_range' => '22 - 25/09/2025',
            'music_enabled' => true,
        ]);

        $this->assertDatabaseHas('post_sections', [
            'post_id' => $post->id,
            'type' => 'gallery_grid',
            'title' => 'Gallery typed',
        ]);

        $this->assertDatabaseHas('post_section_items', [
            'media_id' => $media[0]->id,
            'caption' => 'First image',
        ]);

        $this->assertDatabaseHas('post_section_items', [
            'kind' => 'timeline',
            'media_id' => $media[1]->id,
            'time_label' => 'Ngay 1 - 06:00',
            'title' => 'Bat dau',
        ]);
    }

    public function test_video_block_saves_an_uploaded_video_instead_of_a_link(): void
    {
        Storage::fake('public');
        $this->seed();

        $admin = User::query()->where('email', 'admin@chuaminh.vn')->firstOrFail();
        $post = Post::query()->firstOrFail();

        $upload = $this
            ->actingAs($admin)
            ->postJson(route('admin.media.upload'), [
                'file' => UploadedFile::fake()->create('memory.mp4', 256, 'video/mp4'),
            ])
            ->assertCreated()
            ->assertJsonPath('media.type', 'video');

        $videoId = $upload->json('media.id');

        $this
            ->actingAs($admin)
            ->put(route('admin.memories.editor.update', $post), [
                'title' => $post->title,
                'slug' => $post->slug,
                'template_id' => $post->template_id,
                'category_id' => $post->category_id,
                'cover_media_id' => $post->cover_media_id,
                'status' => 'published',
                'visibility' => 'public',
                'sections' => [
                    [
                        'type' => 'video_embed',
                        'title' => 'Video chuyen di',
                        'media_id' => $videoId,
                        'caption' => 'Video da upload.',
                        'url' => 'https://example.com/not-accepted-as-video.mp4',
                        'layout' => 'clean_video',
                        'is_visible' => '1',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.memories.editor', $post));

        $this->assertDatabaseHas('post_sections', [
            'post_id' => $post->id,
            'type' => 'video_embed',
            'media_id' => $videoId,
            'url' => null,
        ]);
    }

    public function test_video_block_rejects_an_image_media_id(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'admin@chuaminh.vn')->firstOrFail();
        $post = Post::query()->firstOrFail();
        $image = Media::query()->where('type', 'image')->firstOrFail();

        $this
            ->actingAs($admin)
            ->from(route('admin.memories.editor', $post))
            ->put(route('admin.memories.editor.update', $post), [
                'title' => $post->title,
                'slug' => $post->slug,
                'template_id' => $post->template_id,
                'status' => 'published',
                'visibility' => 'public',
                'sections' => [
                    [
                        'type' => 'video_embed',
                        'media_id' => $image->id,
                        'is_visible' => '1',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.memories.editor', $post))
            ->assertSessionHasErrors('sections');
    }
}
