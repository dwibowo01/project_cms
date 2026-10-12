<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\RepairListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairListItem>
 */
class RepairListItemFactory extends Factory
{
    protected $model = RepairListItem::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->sentence(4),
            'qty' => $this->faker->numberBetween(1, 10),
            'unit' => $this->faker->randomElement(['ls', 'pcs', 'set', 'm2']),
            'sort_order' => 1,
        ];
    }
}
