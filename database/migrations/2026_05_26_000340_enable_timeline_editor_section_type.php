<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('section_types')) {
            DB::table('section_types')
                ->where('slug', 'timeline')
                ->update([
                    'category' => 'story',
                    'is_active' => true,
                    'updated_at' => now(),
                ]);
        }

        $this->updateTemplateTimelineSupport(true);
    }

    public function down(): void
    {
        if (Schema::hasTable('section_types')) {
            DB::table('section_types')
                ->where('slug', 'timeline')
                ->update([
                    'category' => 'legacy',
                    'is_active' => false,
                    'updated_at' => now(),
                ]);
        }

        $this->updateTemplateTimelineSupport(false);
    }

    private function updateTemplateTimelineSupport(bool $add): void
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
                    ? array_values(array_unique([...$types, 'timeline']))
                    : array_values(array_filter($types, fn (string $type): bool => $type !== 'timeline'));

                DB::table('templates')
                    ->where('id', $template->id)
                    ->update([
                        'supported_section_types' => json_encode($types),
                        'updated_at' => now(),
                    ]);
            });
    }
};
