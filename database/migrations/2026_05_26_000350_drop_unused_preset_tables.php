<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('section_presets');
        Schema::dropIfExists('template_presets');
    }

    public function down(): void
    {
        Schema::create('template_presets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->json('tokens');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['template_id', 'slug']);
        });

        Schema::create('section_presets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->json('data');
            $table->json('style')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['section_type_id', 'slug']);
        });
    }
};
