<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterItemCategory>
 */
class MasterItemCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
        ];
    }
}
