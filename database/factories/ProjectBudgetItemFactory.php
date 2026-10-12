<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectBudgetItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectBudgetItem>
 */
class ProjectBudgetItemFactory extends Factory
{
    protected $model = ProjectBudgetItem::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->sentence(4),
            'qty' => $this->faker->numberBetween(1, 10),
            'unit' => $this->faker->randomElement(['ls', 'pcs', 'set', 'm2']),
            'unit_price' => $this->faker->numberBetween(50000, 5000000),
            'sort_order' => 1,
        ];
    }
}
