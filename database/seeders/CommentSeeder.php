<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder for creating realistic comments on posts.
 * 
 * Adds multiple random comments to each post from random users,
 * simulating social engagement between users on an Instagram-like platform.
 * Comments use realistic text patterns found on social media photos.
 */
class CommentSeeder extends Seeder
{
    /**
     * Create comments for all posts from random users.
     * 
     * Adds 2-5 comments to each post, ensuring comments come from
     * users other than the post owner. Uses a predefined list of
     * realistic Instagram-style comments.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        // Predefined list of realistic Instagram-style comments
        $commentTexts = [
            'This looks amazing!',
            'Great composition!',
            'Awesome shot! 🔥',
            'Love the colors in this!',
            'Perfect lighting!',
            'Where was this taken?',
            'This made my day!',
            'Incredible vibes 😍',
            'I wish I was there!',
            'Such a cool perspective'
        ];

        foreach ($posts as $post) {
            // Add 2-5 random comments to each post
            $commentCount = rand(2, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                // Get random user (but not the post owner)
                $commenter = $users->where('id', '!=', $post->user_id)->random();

                // Create the comment
                Comment::create([
                    'user_id' => $commenter->id,
                    'post_id' => $post->id,
                    'content' => $commentTexts[array_rand($commentTexts)]
                ]);
            }
        }
    }
}
