<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoryable;
use App\Models\Product;

class CategoryableSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $categories = \App\Models\Category::all();

        foreach ($products as $product) {
            // Mỗi sản phẩm gắn 1-3 category
            foreach ($categories->random(rand(1, 3)) as $category) {
                Categoryable::factory()->create([
                    'categoryable_type' => Product::class,
                    'categoryable_id' => $product->id,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
