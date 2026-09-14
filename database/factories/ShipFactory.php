<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Ship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ship>
 */
class ShipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ship_name' => 'MV ' . $this->faker->lastName() . ' ' . $this->faker->numberBetween(1, 99),
            'ship_type' => $this->faker->randomElement(Ship::SHIP_TYPES),
            'client_id' => Client::factory(),
            'loa_value' => $this->faker->randomFloat(2, 20, 200),
            'loa_unit' => $this->faker->randomElement(Ship::UNITS),
            'lbp_value' => $this->faker->optional()->randomFloat(2, 20, 190),
            'lbp_unit' => $this->faker->randomElement(Ship::UNITS),
            'height_value' => $this->faker->optional()->randomFloat(2, 2, 20),
            'height_unit' => $this->faker->randomElement(Ship::UNITS),
            'width_value' => $this->faker->optional()->randomFloat(2, 5, 40),
            'width_unit' => $this->faker->randomElement(Ship::UNITS),
            'draught_value' => $this->faker->optional()->randomFloat(2, 1, 15),
            'draught_unit' => $this->faker->randomElement(Ship::UNITS),
            'gt' => $this->faker->optional()->randomFloat(2, 100, 50000),
            'nt' => $this->faker->optional()->randomFloat(2, 50, 30000),
            'power_me' => $this->faker->optional()->randomFloat(2, 100, 20000),
        ];
    }
}
