<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        return [
            'order_id' => $order->id,
            'payment_method' => $this->faker->randomElement(PaymentMethod::values()),
            'transaction_id' => strtoupper($this->faker->bothify('TXN-#######')),
            'amount' => $order->total_amount,
            'paid_at' => $this->faker->optional()->dateTimeBetween('-10 days', 'now'),
            'status' => $this->faker->randomElement(PaymentStatus::values()),
        ];
    }

    public function success(): static
    {
        return $this->state(fn() => [
            'status' => PaymentStatus::Success,
            'paid_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn() => [
            'status' => PaymentStatus::Failed,
            'paid_at' => null,
        ]);
    }
}