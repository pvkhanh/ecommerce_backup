<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Enums\Gender;
use App\Enums\UserRole;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(Gender::values()),
            'birthday' => fake()->dateTimeBetween('-40 years', '-18 years'),
            'bio' => fake()->sentence(),
            'role' => fake()->randomElement(UserRole::values()),
            'remember_token' => Str::random(10),
        ];
    }

    public function buyer(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => UserRole::Buyer,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => UserRole::Admin,
        ]);
    }
}
