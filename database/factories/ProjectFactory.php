<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'project_name' => 'Docking '.$this->faker->lastName(),
            'client_id' => Client::factory(),
            'description' => $this->faker->optional()->sentence(),
            'docking_start_date' => now()->toDateString(),
            'docking_end_date' => null,
            'status' => ProjectStatus::ACTIVE,
            'project_type' => ProjectType::DOCKING,
        ];
    }
}
