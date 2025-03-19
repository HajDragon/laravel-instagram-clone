<?php

namespace Database\Factories;

use App\Models\fakeBioTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating Instagram-style bio and title data.
 * 
 * This factory creates realistic user bio and headline content that mimics
 * the style commonly found on Instagram profiles, including emoji usage
 * and typical social media formatting.
 */
class FakeBioTitleFactory extends Factory
{
    /**
     * The name of the model that this factory creates.
     *
     * @var string
     */
    protected $model = fakeBioTitle::class;

    /**
     * Define the default state of the model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'bio' => $this->faker->paragraph(3),
        ];
    }

    /**
     * Configure the factory to generate Instagram-style bios and titles.
     * 
     * Creates social media style content with emojis, line breaks,
     * and typical Instagram profile formatting patterns.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
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