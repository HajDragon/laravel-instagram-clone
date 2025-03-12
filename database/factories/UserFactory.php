<?php

namespace Database\Factories;

use App\Models\fakeBioTitle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'bio_title_id' => function () {
                $bioTitle = fakeBioTitle::inRandomOrder()->first();
                if (!$bioTitle) {
                    $bioTitle = fakeBioTitle::factory()->instagramStyle()->create();
                }
                return $bioTitle->id;
            },
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
