<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(800, 800, 'nature', true, 'image'),
            'type' => $this->faker->randomElement(['avatar', 'banner', 'thumbnail', 'gallery']),
            'alt_text' => $this->faker->sentence(3),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
