<?php

namespace Database\Factories;

use App\Models\Product;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->words(3, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 100000, 10000000),
            'status' => $this->faker->randomElement(ProductStatus::values()),
        ];
    }

    /**
     * Active status
     */
    public function active(): static
    {
        return $this->state(fn() => ['status' => ProductStatus::Active->value]);
    }

    /**
     * Inactive state
     */
    public function inactive(): static
    {
        return $this->state(fn() => ['status' => ProductStatus::Inactive->value]);
    }

    /**
     * Banned status
     */
    public function banned(): static
    {
        return $this->state(fn() => ['status' => ProductStatus::Banned->value]);
    }
}