<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('variant')->nullable();
            $table->json('data');
            $table->json('style')->nullable();
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true)->index();
            $table->timestamps();

            $table->index(['post_id', 'sort_order']);
            $table->index(['type', 'variant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_sections');
    }
};

