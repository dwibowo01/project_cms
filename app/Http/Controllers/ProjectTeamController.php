<?php

namespace App\Http\Controllers;

use App\Enums\ProjectTeamRole;
use App\Http\Requests\ProjectTeam\UpdateProjectTeamRequest;
use App\Models\Project;
use App\Models\ProjectTeam;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectTeamController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->with(['manager.user', 'members.user'])
            ->orderBy('project_no')
            ->paginate(10);

        return view('project-teams.index', [
            'projects' => $projects,
        ]);
    }

    public function edit(Project $project): View
    {
        $project->load(['manager', 'members']);

        return view('project-teams.edit', [
            'project' => $project,
            'members' => $this->eligibleMembers(),
            'managerId' => $project->manager?->user_id,
            'memberIds' => $project->members->pluck('user_id'),
        ]);
    }

    // Replaces the project's entire team in one go: at most one Project
    // Manager Docking plus any number of Member Docking.
    public function update(UpdateProjectTeamRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($project, $data) {
            $project->teamMembers()->delete();

            if (! empty($data['manager_id'])) {
                ProjectTeam::create([
                    'project_id' => $project->id,
                    'user_id' => $data['manager_id'],
                    'role' => ProjectTeamRole::PROJECT_MANAGER_DOCKING->value,
                ]);
            }

            foreach ($data['member_ids'] ?? [] as $userId) {
                ProjectTeam::create([
                    'project_id' => $project->id,
                    'user_id' => $userId,
                    'role' => ProjectTeamRole::MEMBER_DOCKING->value,
                ]);
            }
        });

        return redirect()->route('project-teams.index')
            ->with('success', __('Project team updated successfully.'));
    }

    // Users eligible to be picked as a team member: those belonging to the current tenant.
    private function eligibleMembers(): Collection
    {
        return User::whereHas('tenants', fn ($query) => $query->where('tenant_id', tenant('id')))
            ->orderBy('name')
            ->get();
    }
}
