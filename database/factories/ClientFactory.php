<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => $this->faker->unique()->numerify('CID-####'),
            'client_company_type' => $this->faker->randomElement(Client::COMPANY_TYPES),
            'client_name' => $this->faker->company(),
            'client_phone_country_code' => '+62',
            'client_phone_number' => $this->faker->numerify('8##########'),
            'client_email' => $this->faker->unique()->safeEmail(),
            'company_address_line_1' => $this->faker->streetAddress(),
            'company_address_line_2' => null,
            'country' => 'Indonesia',
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'website' => $this->faker->url(),
            'notes' => null,
        ];
    }
}
