<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@chuaminh.vn'],
            [
                'name' => 'Khanh',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        User::query()->firstOrCreate(
            ['email' => 'love@chuaminh.vn'],
            [
                'name' => 'Nguoi yeu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        $this->call([
            TemplateSeeder::class,
            SectionTypeSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
