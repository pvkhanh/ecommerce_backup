<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\OrderItem;
use App\Models\Payment;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::factory()->count(30)->create();

        foreach ($orders as $order) {
            ShippingAddress::factory()->create([
                'order_id' => $order->id,
            ]);

            OrderItem::factory()->count(rand(1, 5))->create([
                'order_id' => $order->id,
            ]);

            Payment::factory()->create([
                'order_id' => $order->id,
            ]);
        }
    }
}