<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
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

    public function down(): void
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
};
