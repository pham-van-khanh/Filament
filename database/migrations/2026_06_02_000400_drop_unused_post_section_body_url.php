<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->moveLegacyMusicSectionsToPostDetails();
        $this->retireMusicSectionType();

        Schema::table('post_sections', function (Blueprint $table): void {
            $dropColumns = collect(['body', 'url'])
                ->filter(fn (string $column): bool => Schema::hasColumn('post_sections', $column))
                ->values()
                ->all();

            if ($dropColumns !== []) {
                $table->dropColumn($dropColumns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('post_sections', function (Blueprint $table): void {
            if (! Schema::hasColumn('post_sections', 'body')) {
                $table->longText('body')->nullable()->after('headline');
            }

            if (! Schema::hasColumn('post_sections', 'url')) {
                $table->text('url')->nullable()->after('caption');
            }
        });

        if (Schema::hasTable('section_types')) {
            DB::table('section_types')
                ->where('slug', 'music')
                ->update(['category' => 'audio', 'is_active' => true, 'updated_at' => now()]);
        }

        $this->updateTemplateMusicSupport(true);
    }

    private function moveLegacyMusicSectionsToPostDetails(): void
    {
        if (
            ! Schema::hasTable('post_details')
            || ! Schema::hasColumn('post_sections', 'url')
        ) {
            return;
        }

        DB::table('post_sections')
            ->where('type', 'music')
            ->whereNotNull('url')
            ->orderBy('id')
            ->chunkById(100, function ($sections): void {
                foreach ($sections as $section) {
                    $detail = DB::table('post_details')->where('post_id', $section->post_id)->first();

                    if ($detail && filled($detail->music_url)) {
                        continue;
                    }

                    DB::table('post_details')->updateOrInsert(
                        ['post_id' => $section->post_id],
                        [
                            'music_enabled' => true,
                            'music_url' => $section->url,
                            'music_title' => $detail?->music_title ?: ($section->headline ?? null),
                            'music_artist' => $detail?->music_artist ?: ($section->subtitle ?? null),
                            'created_at' => $detail?->created_at ?: now(),
                            'updated_at' => now(),
                        ],
                    );
                }
            });
    }

    private function retireMusicSectionType(): void
    {
        if (Schema::hasTable('section_types')) {
            DB::table('section_types')
                ->where('slug', 'music')
                ->update(['category' => 'legacy', 'is_active' => false, 'updated_at' => now()]);
        }

        $this->updateTemplateMusicSupport(false);
    }

    private function updateTemplateMusicSupport(bool $add): void
    {
        if (! Schema::hasTable('templates')) {
            return;
        }

        DB::table('templates')
            ->select(['id', 'supported_section_types'])
            ->orderBy('id')
            ->each(function ($template) use ($add): void {
                $types = json_decode((string) $template->supported_section_types, true);
                $types = is_array($types) ? $types : [];

                $types = $add
                    ? array_values(array_unique([...$types, 'music']))
                    : array_values(array_filter($types, fn (string $type): bool => $type !== 'music'));

                DB::table('templates')
                    ->where('id', $template->id)
                    ->update([
                        'supported_section_types' => json_encode($types),
                        'updated_at' => now(),
                    ]);
            });
    }
};
