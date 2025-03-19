<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder for creating user accounts in the Instagram clone.
 * 
 * Creates a predictable test user with known credentials and
 * a large number of random users to simulate a social network.
 * Each user will automatically get a bio and title via factory relationship.
 */
class UsersSeeder extends Seeder
{
    /**
     * Create user accounts.
     * 
     * Creates one test user with fixed credentials for easier testing,
     * and many random users to populate the application.
     */
    public function run(): void
    {
        // Create a known user for testing - predictable login credentials
        User::firstOrCreate(
            ['email' => 'test@example.com'],  // Only create if doesn't exist
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional random users with realistic data
        User::factory(101)->create();  // Creates 101 random users
    }
}
