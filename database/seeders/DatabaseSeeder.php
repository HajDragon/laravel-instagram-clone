<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Main database seeder that orchestrates all other seeders.
 * 
 * This seeder runs all other seeders in the correct order to ensure
 * proper data relationships are maintained. The order is important:
 * 1. Users are created first
 * 2. Followers are established between users
 * 3. Posts are created for each user
 * 4. Comments are added to posts
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Executes all seeders in the appropriate order to populate
     * the database with test data that mimics a real Instagram environment.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,          // Create user accounts first
            FollowersSeeder::class,      // Establish follow relationships
            PostsSeeder::class,          // Create posts for users
            CommentSeeder::class,        // Add comments to posts
        ]);
    }
}