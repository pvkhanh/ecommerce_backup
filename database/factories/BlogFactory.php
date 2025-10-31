<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog;
use App\Models\User;
use App\Enums\BlogStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'content' => $this->faker->paragraphs(asText: true),
            'status' => $this->faker->randomElement(BlogStatus::values()),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => BlogStatus::Draft,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => BlogStatus::Published,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => BlogStatus::Archived,
        ]);
    }
}
