<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'receiver_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'province' => $this->faker->state(),
            'district' => $this->faker->city(),
            'ward' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'is_default' => $this->faker->boolean(20),
        ];
    }

    /**
     * Default address state
     */
    public function default(): static
    {
        return $this->state(fn() => ['is_default' => true]);
    }
}