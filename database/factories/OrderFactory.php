<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        // random status row
        $status = $this->faker->randomElement(OrderStatus::values());

        // Order creation time
        $createdAt = $this->faker->dateTimeBetween('-2 months', 'now');

       // Timeline depends on state
        $deliveredAt = null;
        $completedAt = null;
        $cancelledAt = null;

        switch ($status) {
            case OrderStatus::Shipped->value:
                $deliveredAt = $this->faker->dateTimeBetween($createdAt, 'now');
                break;

            case OrderStatus::Completed->value:
                $deliveredAt = $this->faker->dateTimeBetween($createdAt, 'now');
                $completedAt = $this->faker->dateTimeBetween($deliveredAt, 'now');
                break;

            case OrderStatus::Cancelled->value:
                $cancelledAt = $this->faker->dateTimeBetween($createdAt, 'now');
                break;
        }

        $totalAmount = $this->faker->randomFloat(2, 100000, 5000000);
        $shippingFee = $this->faker->randomFloat(2, 15000, 50000);

        return [
            'user_id' => User::factory(),
            'order_number' => strtoupper(Str::random(10)),
            'total_amount' => $totalAmount,
            'shipping_fee' => $shippingFee,
            'customer_note' => $this->faker->optional()->sentence(),
            'admin_note' => $this->faker->optional()->sentence(),
            'status' => $status,
            'delivered_at' => $deliveredAt,
            'completed_at' => $completedAt,
            'cancelled_at' => $cancelledAt,
            'created_at' => $createdAt,
            'updated_at' => now(),
        ];
    }

    /**
* Specific state (if you want to call quickly)
*/
    public function pending(): static
    {
        return $this->state(fn() => ['status' => OrderStatus::Pending]);
    }

    public function paid(): static
    {
        return $this->state(fn() => ['status' => OrderStatus::Paid]);
    }

    public function shipped(): static
    {
        return $this->state(fn() => ['status' => OrderStatus::Shipped]);
    }

    public function completed(): static
    {
        return $this->state(fn() => ['status' => OrderStatus::Completed]);
    }

    public function cancelled(): static
    {
        return $this->state(fn() => ['status' => OrderStatus::Cancelled]);
    }
}