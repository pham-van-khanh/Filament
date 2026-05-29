<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('date_range')->nullable()->after('memory_date_precision');
            $table->boolean('music_enabled')->default(false)->after('mood');
            $table->string('music_url')->nullable()->after('music_enabled');
            $table->string('music_title')->nullable()->after('music_url');
            $table->string('music_artist')->nullable()->after('music_title');
        });

        Schema::table('post_sections', function (Blueprint $table): void {
            $table->foreignId('media_id')->nullable()->after('variant')->constrained('media')->nullOnDelete();
            $table->string('headline')->nullable()->after('media_id');
            $table->longText('body')->nullable()->after('headline');
            $table->text('quote_text')->nullable()->after('body');
            $table->string('quote_author')->nullable()->after('quote_text');
            $table->text('caption')->nullable()->after('quote_author');
            $table->text('url')->nullable()->after('caption');
            $table->string('height')->nullable()->after('url');
            $table->string('layout')->nullable()->after('height');
            $table->string('accent_color')->nullable()->after('layout');
            $table->string('text_align')->nullable()->after('accent_color');
            $table->boolean('autoplay')->default(false)->after('text_align');
            $table->boolean('lightbox_enabled')->default(true)->after('autoplay');
            $table->string('overlay_style')->nullable()->after('lightbox_enabled');
        });

        Schema::create('post_section_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('kind')->default('item')->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('value')->nullable();
            $table->string('label')->nullable();
            $table->string('time_label')->nullable();
            $table->text('body')->nullable();
            $table->text('caption')->nullable();
            $table->text('url')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();

            $table->index(['post_section_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_section_items');

        Schema::table('post_sections', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('media_id');
            $table->dropColumn([
                'headline',
                'body',
                'quote_text',
                'quote_author',
                'caption',
                'url',
                'height',
                'layout',
                'accent_color',
                'text_align',
                'autoplay',
                'lightbox_enabled',
                'overlay_style',
            ]);
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
};
