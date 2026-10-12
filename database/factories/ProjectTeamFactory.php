<?php

namespace Database\Factories;

use App\Enums\ProjectTeamRole;
use App\Models\Project;
use App\Models\ProjectTeam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectTeam>
 */
class ProjectTeamFactory extends Factory
{
    protected $model = ProjectTeam::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'role' => ProjectTeamRole::MEMBER_DOCKING,
        ];
    }
}
