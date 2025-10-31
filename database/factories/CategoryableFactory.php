<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categoryable;
use App\Models\Category;
use App\Models\Product;
use App\Models\Blog;

class CategoryableFactory extends Factory
{
    protected $model = Categoryable::class;

    public function definition(): array
    {
        // Randomly select model to attach category
        $categoryableType = $this->faker->randomElement([
            Product::class,
            Blog::class, // if you have a Blog with attached categories
        ]);

        // Randomly get an existing record or create a new record
        $categoryableId = $categoryableType::inRandomOrder()->first()?->id ?? $categoryableType::factory()->create()->id;

        $categoryId = Category::inRandomOrder()->first()?->id ?? Category::factory()->create()->id;

        return [
            'category_id' => $categoryId,
            'categoryable_id' => $categoryableId,
            'categoryable_type' => $categoryableType,
        ];
    }
}