<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Database\Seeder;

class FollowersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // For each user, create random follows
        foreach ($users as $user) {
            // Get random users to follow (excluding self)
            $potentialFollowings = $users->where('id', '!=', $user->id)->random(rand(1, 100));

            foreach ($potentialFollowings as $following) {
                Follower::create([
                    'user_id' => $user->id,
                    'following_id' => $following->id
                ]);
            }
        }
    }
}