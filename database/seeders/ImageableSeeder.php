<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Imageable;

class ImageableSeeder extends Seeder
{
    public function run(): void
    {
        Imageable::factory()->count(40)->create();
    }
}
