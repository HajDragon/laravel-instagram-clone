<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();
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

                Comment::create([
                    'user_id' => $commenter->id,
                    'post_id' => $post->id,
                    'content' => $commentTexts[array_rand($commentTexts)]
                ]);
            }
        }
    }
}
