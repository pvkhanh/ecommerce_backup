<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MailRecipient;
use App\Models\Mail;
use App\Models\User;
use App\Enums\MailRecipientStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailRecipient>
 */
class MailRecipientFactory extends Factory
{
    protected $model = MailRecipient::class;

    public function definition(): array
    {
        return [
            'mail_id' => Mail::factory(),
            'user_id' => User::factory(),
            'email' => $this->faker->safeEmail(),
            'name' => $this->faker->name(),
            'status' => $this->faker->randomElement(MailRecipientStatus::values()),
            'error_log' => null,
        ];
    }

    public function sent(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => MailRecipientStatus::Sent,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => MailRecipientStatus::Failed,
            'error_log' => $this->faker->sentence(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => MailRecipientStatus::Pending,
        ]);
    }
}