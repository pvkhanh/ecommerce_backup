<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'variant_id' => ProductVariant::inRandomOrder()->first()?->id ?? ProductVariant::factory(),
        ];
    }
}