<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // mỗi user có 1-3 địa chỉ
            UserAddress::factory()->count(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);
        }
    }
}