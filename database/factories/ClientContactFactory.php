<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientContact>
 */
class ClientContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'phone_number' => $this->faker->numerify('8##########'),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
