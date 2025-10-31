<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserAddressSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ImageSeeder::class,
            CategoryableSeeder::class,
                // ProductReviewSeeder::class,
            CartItemSeeder::class,
            WishlistSeeder::class,
            OrderSeeder::class,
                // NotificationSeeder::class,
            MailSeeder::class,
                // BlogSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
