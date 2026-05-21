<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('preview_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->json('default_data_schema');
            $table->json('default_style_schema')->nullable();
            $table->json('available_variants');
            $table->json('supported_templates')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_types');
    }
};

