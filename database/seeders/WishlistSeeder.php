<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        Wishlist::factory()->count(30)->create();
    }
}