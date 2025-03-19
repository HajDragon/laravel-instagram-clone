<?php

namespace Database\Seeders;

use App\Models\fakeBioTitle;
use Illuminate\Database\Seeder;

/**
 * Seeder for creating Instagram-style bio and title content.
 * 
 * Generates a large number of realistic bio/title combinations
 * with typical Instagram styling like emojis and catchphrases.
 * These will be associated with users in the system.
 */
class FakeBioTitleSeeder extends Seeder
{
    /**
     * Create Instagram-style bio and title entries.
     * 
     * Uses the instagramStyle state on the fakeBioTitle factory
     * to generate realistic social media profile content.
     */
    public function run(): void
    {
        // Create 200 fake bio titles using the Instagram-style state
        // This specialized factory state adds emojis and social media formatting
        fakeBioTitle::factory()->instagramStyle()->count(200)->create();
    }
}