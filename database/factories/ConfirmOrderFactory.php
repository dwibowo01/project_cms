<?php

namespace Database\Factories;

use App\Models\ConfirmOrder;
use App\Models\Project;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConfirmOrder>
 */
class ConfirmOrderFactory extends Factory
{
    protected $model = ConfirmOrder::class;

    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::factory(),
            'project_id' => Project::factory(),
            'docking_start_date' => now()->toDateString(),
            'ppn' => $this->faker->boolean(),
        ];
    }
}
