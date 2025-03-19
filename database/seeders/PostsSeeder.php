<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PostsSeeder extends Seeder
{
    /**
     * Known working Picsum photo IDs to avoid verification
     */
    protected $knownGoodIds = [
        1,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        2,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        3,
        30,
        31,
        32,
        33,
        34,
        35,
        36,
        37,
        38,
        39,
        4,
        40,
        41,
        42,
        43,
        44,
        45,
        46,
        47,
        48,
        49,
        5,
        50,
        51,
        52,
        53,
        54,
        55,
        56,
        57,
        58,
        59,
        6,
        60,
        61,
        62,
        63,
        64,
        65,
        66,
        67,
        68,
        69,
        7,
        70,
        71,
        72,
        73,
        74,
        75,
        76,
        77,
        78,
        79,
        8,
        80,
        81,
        82,
        83,
        84,
        85,
        86,
        87,
        88,
        89,
        9,
        90,
        91,
        92,
        93,
        94,
        95,
        96,
        97,
        98,
        99,
        100
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing posts first
        $this->deleteExistingPosts();

        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('No users found. Cannot create posts.');
            return;
        }

        $this->command->info('Creating posts for ' . $users->count() . ' users...');
        $bar = $this->command->getOutput()->createProgressBar($users->count());

        // For each user, create 9 posts
        foreach ($users as $user) {
            // Create 9 posts for each user
            for ($i = 0; $i < 9; $i++) {
                // Use known good IDs instead of trying to verify random ones
                $randomNumber = $this->knownGoodIds[array_rand($this->knownGoodIds)];

                // Create a post with the direct URL
                Post::create([
                    'user_id' => $user->id,
                    'image_path' => "picsum:{$randomNumber}",
                    'caption' => 'Post #' . ($i + 1) . ' by ' . $user->name . ' #instagram #laravel',
                    'location' => fake()->city() . ', ' . fake()->country(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->getOutput()->newLine();
        $this->command->info('Total posts created: ' . Post::count());
    }

    /**
     * Delete all existing posts and their associated images
     */
    private function deleteExistingPosts(): void
    {
        $this->command->info('Deleting existing posts...');

        // Get all post images before deleting records
        $posts = Post::all(['id', 'image_path']);
        $imageCount = 0;

        // Delete local images if they exist (for backward compatibility)
        foreach ($posts as $post) {
            if (
                $post->image_path && !str_starts_with($post->image_path, 'picsum:') &&
                Storage::disk('public')->exists($post->image_path)
            ) {
                Storage::disk('public')->delete($post->image_path);
                $imageCount++;
            }
        }

        // Delete all comments associated with posts (if comments table exists)
        if (Schema::hasTable('comments')) {
            DB::table('comments')->truncate();
            $this->command->line('Comments deleted');
        }

        // Delete all posts
        Post::truncate();

        $this->command->info("Deleted {$posts->count()} posts and {$imageCount} local images");
    }
}
