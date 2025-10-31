<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        // Randomly get 1 product (or create if not available)
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $variant = $product->variants()->inRandomOrder()->first() ?? ProductVariant::factory()->create(['product_id' => $product->id]);

        return [
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => fake()->numberBetween(1, 5),
            'price' => $variant->price ?? $product->price,
        ];
    }
}