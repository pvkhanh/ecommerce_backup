<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mail;
use App\Enums\MailType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mail>
 */
class MailFactory extends Factory
{
    protected $model = Mail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'template_key' => $this->faker->word(),
            'type' => $this->faker->randomElement(MailType::values()),
            'sender_email' => $this->faker->safeEmail(),
            'variables' => [
                'user_name' => $this->faker->name(),
                'link' => $this->faker->url(),
            ],
        ];
    }

    /**
     * Indicate a system mail type.
     */
    public function system(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => MailType::System,
        ]);
    }

    /**
     * Indicate a user mail type.
     */
    public function user(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => MailType::User,
        ]);
    }

    /**
     * Indicate a marketing mail type.
     */
    public function marketing(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => MailType::Marketing,
        ]);
    }
}