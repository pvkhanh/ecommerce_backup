<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        CartItem::factory()->count(40)->create();
    }
}