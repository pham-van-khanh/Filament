<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->backfillPostDetails();
        $this->backfillTypedSections();
        $this->retireLegacySectionTypes();

        Schema::table('post_media', function (Blueprint $table): void {
            $table->dropColumn('metadata');
        });

        Schema::table('post_sections', function (Blueprint $table): void {
            $table->dropColumn([
                'data',
                'style',
                'settings',
                'accent_color',
                'text_align',
                'lightbox_enabled',
                'overlay_style',
            ]);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex(['sort_order']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn([
                'content',
                'memory_date_precision',
                'location_lat',
                'location_lng',
                'sort_order',
                'settings',
            ]);
        });
    }

    public function down(): void
    {
        $this->restoreLegacySectionTypes();

        Schema::table('posts', function (Blueprint $table): void {
            $table->longText('content')->nullable();
            $table->string('memory_date_precision')->nullable();
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->json('settings')->nullable();
        });

        Schema::table('post_sections', function (Blueprint $table): void {
            $table->json('data')->nullable();
            $table->json('style')->nullable();
            $table->json('settings')->nullable();
            $table->string('accent_color')->nullable();
            $table->string('text_align')->nullable();
            $table->boolean('lightbox_enabled')->default(true);
            $table->string('overlay_style')->nullable();
        });

        Schema::table('post_media', function (Blueprint $table): void {
            $table->json('metadata')->nullable();
        });
    }

    private function backfillPostDetails(): void
    {
        if (! Schema::hasColumn('posts', 'settings') || ! Schema::hasTable('post_details')) {
            return;
        }

        DB::table('posts')
            ->whereNotNull('settings')
            ->orderBy('id')
            ->chunkById(100, function ($posts): void {
                foreach ($posts as $post) {
                    $existing = DB::table('post_details')->where('post_id', $post->id)->first();

                    if ($existing && ($existing->date_range || $existing->music_enabled || $existing->music_url || $existing->music_title || $existing->music_artist)) {
                        continue;
                    }

                    $settings = $this->decode($post->settings);
                    $music = $settings['music'] ?? [];

                    if (! ($settings['date_range'] ?? null) && empty($music)) {
                        continue;
                    }

                    DB::table('post_details')->updateOrInsert(
                        ['post_id' => $post->id],
                        [
                            'date_range' => $settings['date_range'] ?? null,
                            'music_enabled' => (bool) ($music['enabled'] ?? false),
                            'music_url' => $music['url'] ?? $music['src'] ?? null,
                            'music_title' => $music['title'] ?? null,
                            'music_artist' => $music['artist'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    );
                }
            });
    }

    private function retireLegacySectionTypes(): void
    {
        if (! Schema::hasTable('section_types')) {
            return;
        }

        DB::table('section_types')
            ->whereIn('slug', ['rich_text', 'image_text', 'ending'])
            ->update(['is_active' => false, 'updated_at' => now()]);

        DB::table('section_types')
            ->where('slug', 'video_embed')
            ->update(['name' => 'Video Upload', 'updated_at' => now()]);
    }

    private function restoreLegacySectionTypes(): void
    {
        if (! Schema::hasTable('section_types')) {
            return;
        }

        DB::table('section_types')
            ->whereIn('slug', ['rich_text', 'image_text', 'ending'])
            ->update(['is_active' => true, 'updated_at' => now()]);

        DB::table('section_types')
            ->where('slug', 'video_embed')
            ->update(['name' => 'Video Block', 'updated_at' => now()]);
    }

    private function backfillTypedSections(): void
    {
        if (! Schema::hasColumn('post_sections', 'data')) {
            return;
        }

        DB::table('post_sections')
            ->orderBy('id')
            ->chunkById(100, function ($sections): void {
                foreach ($sections as $section) {
                    $data = $this->decode($section->data);
                    $style = $this->decode($section->style);
                    $updates = [];

                    $put = function (string $column, mixed $value) use ($section, &$updates): void {
                        if (($section->{$column} === null || $section->{$column} === '') && filled($value)) {
                            $updates[$column] = $value;
                        }
                    };

                    $put('height', $style['--section-height'] ?? null);

                    switch ($section->type) {
                        case 'hero_image':
                            $this->backfillHero($put, $data);
                            break;
                        case 'single_image':
                            $this->backfillSingleImage($put, $data);
                            break;
                        case 'image_text':
                            $this->backfillImageText($put, $data);
                            break;
                        case 'quote':
                            $this->backfillQuote($put, $data);
                            break;
                        case 'rich_text':
                            $put('body', $data['html'] ?? null);
                            break;
                        case 'video_embed':
                            $this->backfillVideo($put, $data);
                            break;
                        case 'music':
                            $this->backfillMusic($put, $data);
                            break;
                        case 'ending':
                            $this->backfillEnding($put, $data);
                            break;
                        case 'gallery_grid':
                        case 'gallery_slider':
                            $put('layout', $data['layout'] ?? $section->variant);
                            break;
                    }

                    if ($section->type === 'gallery_slider' && ! empty($data['autoplay'])) {
                        $updates['autoplay'] = true;
                    }

                    if (! empty($updates)) {
                        DB::table('post_sections')->where('id', $section->id)->update($updates);
                    }

                    $this->backfillItems($section->id, $section->type, $data);
                }
            });
    }

    private function backfillHero(\Closure $put, array $data): void
    {
        $put('media_id', $data['media_id'] ?? null);
        $put('headline', $data['headline'] ?? null);
        $put('caption', $data['subheadline'] ?? null);
    }

    private function backfillSingleImage(\Closure $put, array $data): void
    {
        $put('media_id', $data['media_id'] ?? null);
        $put('caption', $data['caption'] ?? null);
    }

    private function backfillImageText(\Closure $put, array $data): void
    {
        $put('media_id', $data['media_id'] ?? null);
        $put('body', $data['body'] ?? null);
        $put('caption', $data['caption'] ?? null);
    }

    private function backfillQuote(\Closure $put, array $data): void
    {
        $put('quote_text', $data['quote'] ?? null);
        $put('quote_author', $data['author'] ?? null);
    }

    private function backfillVideo(\Closure $put, array $data): void
    {
        $put('url', $data['url'] ?? $data['embed_url'] ?? null);
        $put('caption', $data['caption'] ?? null);
    }

    private function backfillMusic(\Closure $put, array $data): void
    {
        $put('url', $data['url'] ?? $data['src'] ?? null);
        $put('headline', $data['title'] ?? null);
        $put('subtitle', $data['artist'] ?? null);
        $put('caption', $data['caption'] ?? null);
    }

    private function backfillEnding(\Closure $put, array $data): void
    {
        $put('headline', $data['title'] ?? null);
        $put('body', $data['body'] ?? null);
    }

    private function backfillItems(int $sectionId, string $type, array $data): void
    {
        if (DB::table('post_section_items')->where('post_section_id', $sectionId)->exists()) {
            return;
        }

        $items = match ($type) {
            'stats' => collect($data['items'] ?? [])->map(fn ($item): array => [
                'value' => $item[0] ?? null,
                'label' => $item[1] ?? null,
            ])->all(),
            'gallery_grid' => collect(! empty($data['items']) ? $data['items'] : ($data['media_ids'] ?? []))->map(fn ($item): array => [
                'media_id' => is_array($item) ? ($item['media_id'] ?? null) : $item,
                'caption' => is_array($item) ? ($item['caption'] ?? null) : null,
            ])->all(),
            'gallery_slider' => collect($data['slides'] ?? [])->map(fn ($item): array => [
                'media_id' => $item['media_id'] ?? null,
                'caption' => $item['caption'] ?? null,
            ])->all(),
            'timeline' => collect($data['items'] ?? [])->map(fn ($item): array => [
                'media_id' => $item['media_id'] ?? null,
                'time_label' => $item['time'] ?? null,
                'title' => $item['title'] ?? null,
                'body' => $item['body'] ?? null,
            ])->all(),
            default => [],
        };

        foreach ($items as $index => $item) {
            DB::table('post_section_items')->insert([
                'post_section_id' => $sectionId,
                'media_id' => $item['media_id'] ?? null,
                'kind' => $type,
                'title' => $item['title'] ?? null,
                'subtitle' => $item['subtitle'] ?? null,
                'value' => $item['value'] ?? null,
                'label' => $item['label'] ?? null,
                'time_label' => $item['time_label'] ?? null,
                'body' => $item['body'] ?? null,
                'caption' => $item['caption'] ?? null,
                'url' => $item['url'] ?? null,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function decode(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode((string) $value, true);

        return is_array($decoded) ? $decoded : [];
    }
};
