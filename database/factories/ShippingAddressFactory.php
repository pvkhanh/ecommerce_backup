<?php

namespace Database\Factories;

use App\Models\ShippingAddress;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingAddressFactory extends Factory
{
    protected $model = ShippingAddress::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'receiver_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('0#########'),
            'address' => $this->faker->streetAddress(),
            'province' => $this->faker->city(),
            'district' => $this->faker->citySuffix(),
            'ward' => $this->faker->streetName(),
            'postal_code' => $this->faker->postcode(),
        ];
    }
}
