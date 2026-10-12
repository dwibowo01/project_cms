<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('client')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($inner) use ($search) {
                    $inner->where('project_no', 'like', "%{$search}%")
                        ->orWhere('project_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'statuses' => ProjectStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'clients' => Client::orderBy('client_name')->get(),
            'statuses' => ProjectStatus::cases(),
            'types' => ProjectType::cases(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', __('Project created successfully.'));
    }

    public function show(Project $project): View
    {
        return view('projects.show', [
            'project' => $project->load('client', 'confirmOrders.quotation.revisions', 'manager.user', 'members.user'),
        ]);
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
            'clients' => Client::orderBy('client_name')->get(),
            'statuses' => ProjectStatus::cases(),
            'types' => ProjectType::cases(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', __('Project updated successfully.'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', __('Project deleted successfully.'));
    }
}
