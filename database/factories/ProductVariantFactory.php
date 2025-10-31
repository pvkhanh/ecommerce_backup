<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => ucfirst($this->faker->words(2, true)), // VD: "Red Shirt"
            'sku' => strtoupper(Str::random(10)), // Unique SKU code
            'price' => $this->faker->randomFloat(2, 100000, 2000000),
        ];
    }

    /**
     * Cheap variant state
     */
    public function cheap(): static
    {
        return $this->state(fn() => ['price' => $this->faker->randomFloat(2, 50000, 200000)]);
    }

    /**
     * Expensive variant state
     */
    public function premium(): static
    {
        return $this->state(fn() => ['price' => $this->faker->randomFloat(2, 2000000, 10000000)]);
    }
}