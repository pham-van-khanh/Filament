<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('relation')->nullable();
            $table->text('content');
            $table->boolean('is_private')->default(false)->index();
            $table->string('status')->default('pending')->index();
            $table->timestamps();

            $table->index(['post_id', 'status', 'created_at']);
        });

        Schema::create('reactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->string('reaction_type')->default('love')->index();
            $table->timestamps();

            $table->index(['post_id', 'reaction_type']);
            $table->unique(['post_id', 'session_id', 'reaction_type']);
        });

        Schema::create('private_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('message');
            $table->string('status')->default('unread')->index();
            $table->timestamps();

            $table->index(['post_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_messages');
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('comments');
    }
};
