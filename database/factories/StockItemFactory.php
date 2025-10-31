<?php

namespace Database\Factories;

use App\Models\StockItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockItemFactory extends Factory
{
    protected $model = StockItem::class;

    public function definition(): array
    {
        return [
            'variant_id' => ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(0, 500),
            'location' => $this->faker->randomElement([
                'Kho chính Hà Nội',
                'Kho TP. HCM',
                'Kho Đà Nẵng',
                'Kho Cần Thơ'
            ]),
        ];
    }

    /**
     * Out of stock status
     */
    public function outOfStock(): static
    {
        return $this->state(fn() => ['quantity' => 0]);
    }

    /**
     * High stock status
     */
    public function inStock(): static
    {
        return $this->state(fn() => ['quantity' => $this->faker->numberBetween(100, 500)]);
    }
}