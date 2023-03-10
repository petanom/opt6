<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Orders;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders>
 */
class OrdersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Orders::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => rand(1678371380, 1677766581),
            'phone' => ['+7', '8'][rand(0, 1)] . rand(900, 999) . rand(1000000, 9999999),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'summ' => 3000,
        ];
    }
}
