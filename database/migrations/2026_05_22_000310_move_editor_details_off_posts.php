<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_details', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('date_range')->nullable();
            $table->boolean('music_enabled')->default(false);
            $table->string('music_url')->nullable();
            $table->string('music_title')->nullable();
            $table->string('music_artist')->nullable();
            $table->timestamps();
        });

        if (Schema::hasColumn('posts', 'date_range')) {
            DB::table('posts')
                ->select(['id', 'date_range', 'music_enabled', 'music_url', 'music_title', 'music_artist'])
                ->orderBy('id')
                ->chunk(100, function ($posts): void {
                    foreach ($posts as $post) {
                        if (! $post->date_range && ! $post->music_enabled && ! $post->music_url && ! $post->music_title && ! $post->music_artist) {
                            continue;
                        }

                        DB::table('post_details')->updateOrInsert(
                            ['post_id' => $post->id],
                            [
                                'date_range' => $post->date_range,
                                'music_enabled' => (bool) $post->music_enabled,
                                'music_url' => $post->music_url,
                                'music_title' => $post->music_title,
                                'music_artist' => $post->music_artist,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ],
                        );
                    }
                });

            Schema::table('posts', function (Blueprint $table): void {
                $table->dropColumn([
                    'date_range',
                    'music_enabled',
                    'music_url',
                    'music_title',
                    'music_artist',
                ]);
            });
        }
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('date_range')->nullable()->after('memory_date_precision');
            $table->boolean('music_enabled')->default(false)->after('mood');
            $table->string('music_url')->nullable()->after('music_enabled');
            $table->string('music_title')->nullable()->after('music_url');
            $table->string('music_artist')->nullable()->after('music_title');
        });

        DB::table('post_details')
            ->select(['post_id', 'date_range', 'music_enabled', 'music_url', 'music_title', 'music_artist'])
            ->orderBy('post_id')
            ->chunk(100, function ($details): void {
                foreach ($details as $detail) {
                    DB::table('posts')
                        ->where('id', $detail->post_id)
                        ->update([
                            'date_range' => $detail->date_range,
                            'music_enabled' => (bool) $detail->music_enabled,
                            'music_url' => $detail->music_url,
                            'music_title' => $detail->music_title,
                            'music_artist' => $detail->music_artist,
                        ]);
                }
            });

        Schema::dropIfExists('post_details');
    }
};
