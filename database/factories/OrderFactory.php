<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . $this->faker->unique()->randomNumber(5),
            'receipt_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'marking' => 'MARK-' . $this->faker->randomNumber(4),
            'customer_order_number' => 'CUST-' . $this->faker->randomNumber(4),
            'delivery_type' => $this->faker->randomElement(['standard', 'express', 'economy']),
            'departure_place' => $this->faker->city(),
            'customer_code' => 'CC-' . $this->faker->randomNumber(3),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'bank_transfer']),
            'purpose' => $this->faker->sentence(3),
            'name' => $this->faker->name(),
            'cargo_type' => $this->faker->randomElement(['fragile', 'heavy', 'perishable', 'standard']),
            'place' => $this->faker->randomNumber(1) + 1,
            'weight' => $this->faker->randomFloat(2, 0.1, 100),
            'volume' => $this->faker->randomFloat(2, 0.1, 10),
            'density' => $this->faker->randomFloat(2, 0.1, 5),
            'cargo_cost' => $this->faker->randomFloat(2, 100, 10000),
            'insurance' => $this->faker->randomFloat(2, 0, 1000),
            'rate' => $this->faker->randomFloat(2, 10, 100),
            'delivery_cost' => $this->faker->randomFloat(2, 100, 5000),
            'packaging_cost' => $this->faker->randomFloat(2, 0, 500),
            'loading_unloading_cost' => $this->faker->randomFloat(2, 0, 500),
            'total_invoice_amount' => $this->faker->randomFloat(2, 500, 15000),
            'cod' => $this->faker->randomFloat(2, 0, 1000),
            'recipient' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'brand_name' => $this->faker->company(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'recipient_address' => $this->faker->address(),
        ];
    }
}
