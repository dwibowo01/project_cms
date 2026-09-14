<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    protected $model = QuotationItem::class;

    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::factory(),
            'name' => $this->faker->sentence(4),
            'qty' => $this->faker->numberBetween(1, 10),
            'unit' => $this->faker->randomElement(['ls', 'pcs', 'set', 'm2']),
            'unit_price' => $this->faker->numberBetween(50000, 5000000),
            'sort_order' => 1,
        ];
    }
}
