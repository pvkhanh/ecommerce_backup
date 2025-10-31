<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Imageable;
use App\Models\User;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 10 ảnh mẫu
        $images = Image::factory(10)->create();

        // Lấy 3 user đầu để gắn avatar
        $users = User::take(3)->get();

        foreach ($users as $user) {
            $image = $images->random();
            Imageable::create([
                'image_id' => $image->id,
                'imageable_id' => $user->id,
                'imageable_type' => User::class,
                'is_main' => true,
                'position' => 1,
            ]);
        }
    }
}
