<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(2, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'parent_id' => null, // assign later if it is a subcategory
            'position' => $this->faker->numberBetween(1, 100),
        ];
    }

   /**
* Subcategory status
*/
    public function child(Category $parent): static
    {
        return $this->state(fn() => [
            'parent_id' => $parent->id,
            'level' => $parent->level + 1,
        ]);
    }
}