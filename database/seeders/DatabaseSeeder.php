<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FakeBioTitleSeeder::class,
            UsersSeeder::class, // Changed from UserSeeder to UsersSeeder
            FollowersSeeder::class,
        ]);
    }
}