<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'type' => $this->faker->randomElement(NotificationType::values()),
            'title' => $this->faker->sentence(6, true),
            'message' => $this->faker->paragraph(2, true),
            'variables' => [
                'order_id' => $this->faker->optional()->randomNumber(),
                'product_id' => $this->faker->optional()->randomNumber(),
            ],
            'is_read' => $this->faker->boolean(30),
            'read_at' => null,
            'expires_at' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
        ];
    }

    public function read(): static
    {
        return $this->state(fn() => [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function unread(): static
    {
        return $this->state(fn() => [
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}
