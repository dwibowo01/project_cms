<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\RepairListItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RepairListController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->whereHas('confirmOrders')
            ->with('client')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($inner) use ($search) {
                    $inner->where('project_no', 'like', "%{$search}%")
                        ->orWhere('project_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('project_no')
            ->paginate(10)
            ->withQueryString();

        return view('repair-lists.index', [
            'projects' => $projects,
        ]);
    }

    public function show(Project $project): View
    {
        $this->ensureSeeded($project);

        return view('repair-lists.show', [
            'project' => $project,
            'items' => RepairListItem::treeForProject($project->id),
            'picOptions' => $project->repairListPicOptions(),
        ]);
    }

    // The Repair List is cloned (name/qty/unit only, no pricing) from the
    // project's confirmed Quotation the first time it's opened.
    private function ensureSeeded(Project $project): void
    {
        if (RepairListItem::query()->forProject($project->id)->exists()) {
            return;
        }

        $quotation = $project->confirmOrders()->latest()->first()?->quotation;

        if ($quotation) {
            RepairListItem::cloneFromQuotation($project->id, $quotation->id);
        }
    }
}
