<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Database\Seeder;

/**
 * Seeder for creating follower relationships between users.
 * 
 * Builds a social network graph by creating follower relationships
 * between users, simulating the Instagram follow system. Each user
 * will follow a random number of other users in the system.
 */
class FollowersSeeder extends Seeder
{
    /**
     * Create follower relationships between users.
     * 
     * For each user in the system, creates 1-100 follow relationships
     * with other randomly selected users, ensuring users don't follow themselves.
     */
    public function run(): void
    {
        // Get all users from the database
        $users = User::all();

        // For each user, create random follows to other users
        foreach ($users as $user) {
            // Get random users to follow (excluding self)
            // Between 1 and 100 users will be followed
            $potentialFollowings = $users->where('id', '!=', $user->id)->random(rand(1, 100));

            // Create follower relationship records
            foreach ($potentialFollowings as $following) {
                Follower::create([
                    'user_id' => $user->id,         // Current user follows...
                    'following_id' => $following->id // ...this other user
                ]);
            }
        }
    }
}