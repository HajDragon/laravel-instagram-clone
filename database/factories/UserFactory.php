<?php

namespace Database\Factories;

use App\Models\fakeBioTitle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory for generating user accounts with profile information.
 * 
 * Creates user accounts with realistic data including names, emails, and
 * associated Instagram-style bio and title information.
 */
class UserFactory extends Factory
{
    /**
     * The default password to use for test users.
     *
     * @var string|null
     */
    protected static ?string $password;

    /**
     * Define the default state of the model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'bio_title_id' => function () {
                // Find an existing bio title or create a new one
                $bioTitle = fakeBioTitle::inRandomOrder()->first();
                if (!$bioTitle) {
                    $bioTitle = fakeBioTitle::factory()->instagramStyle()->create();
                }
                return $bioTitle->id;
            },
        ];
    }

    /**
     * Configure the factory to create a user with an unverified email.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
