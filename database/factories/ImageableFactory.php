<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Image;

class ImageableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image_id' => Image::factory(),
            'imageable_id' => 1, // sẽ ghi đè khi attach thật
            'imageable_type' => 'App\\Models\\User', // mặc định ví dụ
            'is_main' => $this->faker->boolean(50),
            'position' => $this->faker->numberBetween(1, 10),
        ];
    }
}
