<?php

namespace Database\Factories;

use App\Models\fakeBioTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

class FakeBioTitleFactory extends Factory
{
    protected $model = fakeBioTitle::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'bio' => $this->faker->paragraph(3),
        ];
    }

    public function instagramStyle(): Factory
    {
        return $this->state(function (array $attributes) {
            $phrases = [
                'Living my best life',
                'Adventure Seeker',
                'Photography Lover',
                'Coffee Addict',
                'Fitness Enthusiast',
                'Travel Junkie',
                'Food Explorer',
                'Creative Mind',
                'Music Lover',
                'Digital Nomad'
            ];

            $bios = [
                "✨ {$this->faker->catchPhrase()}\n📍 {$this->faker->city()}, {$this->faker->country()}\n📸 {$this->faker->sentence()}",
                "📚 {$this->faker->jobTitle()} | 🌍 Explorer\n💫 {$this->faker->catchPhrase()}\n🔗 {$this->faker->url()}",
                "Just a {$this->faker->word()} enthusiast sharing my journey\n✨ {$this->faker->catchPhrase()}\n📩 DM for collabs",
                "{$this->faker->randomElement(['📸', '🎬', '🎨', '✏️'])} Creator\n{$this->faker->randomElement(['🌿', '🌊', '🏔️', '🌴'])} Nature lover\n💌 {$this->faker->email()}",
            ];

            return [
                'title' => $this->faker->randomElement($phrases),
                'bio' => $this->faker->randomElement($bios)
            ];
        });
    }
}