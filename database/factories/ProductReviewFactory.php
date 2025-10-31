<?php

namespace Database\Factories;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(ReviewStatus::values()),
        ];
    }

    /**
     * Approved status
     */
    public function approved(): static
    {
        return $this->state(fn() => ['status' => ReviewStatus::Approved]);
    }

    /**
     * Pending status
     */
    public function pending(): static
    {
        return $this->state(fn() => ['status' => ReviewStatus::Pending]);
    }

    /**
     * Rejected Status
     */
    public function rejected(): static
    {
        return $this->state(fn() => ['status' => ReviewStatus::Rejected]);
    }
}