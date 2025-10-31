<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'variant_id' => ProductVariant::inRandomOrder()->first()?->id ?? ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'selected' => $this->faker->boolean(80),// 80% of products are selected
        ];
    }
}
