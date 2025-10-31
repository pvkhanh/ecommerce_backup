<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 10 danh mục chính
        Category::factory()->count(10)->create()->each(function ($category) {
            // Tạo 2-4 danh mục con
            Category::factory()->count(rand(2, 4))->child($category)->create();
        });
    }
}