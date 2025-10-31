<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 5 admin
        User::factory()->count(5)->admin()->create();

        // Tạo 20 buyer
        User::factory()->count(20)->buyer()->create();
    }
}
