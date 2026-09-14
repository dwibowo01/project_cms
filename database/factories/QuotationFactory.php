<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\Ship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotation>
 */
class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'ship_id' => Ship::factory(),
            'docking_year' => (string) now()->year,
            'survey_type' => $this->faker->randomElement(Quotation::SURVEY_TYPES),
            'quotation_date' => now()->toDateString(),
        ];
    }
}
