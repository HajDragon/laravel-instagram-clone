<?php

namespace Database\Seeders;

use App\Models\fakeBioTitle;
use Illuminate\Database\Seeder;

class FakeBioTitleSeeder extends Seeder
{
    public function run(): void
    {
        // Create 200 fake bio titles using the Instagram-style state
        fakeBioTitle::factory()->instagramStyle()->count(200)->create();
    }
}