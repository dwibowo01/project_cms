<?php

namespace Database\Factories;

use App\Models\MasterItem;
use App\Models\MasterItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterItem>
 */
class MasterItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => MasterItemCategory::factory(),
            'parent_id' => null,
            'name' => $this->faker->sentence(4),
            'qty' => null,
            'unit' => $this->faker->randomElement(['Lot', 'Day', 'Pcs', 'M2']),
            'unit_price' => $this->faker->randomFloat(2, 10000, 5000000),
            'sort_order' => 0,
        ];
    }

    public function childOf(MasterItem $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $parent->category_id,
            'parent_id' => $parent->id,
        ]);
    }
}
