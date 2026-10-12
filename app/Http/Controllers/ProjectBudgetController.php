<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectBudgetItem;
use App\Models\RepairListItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectBudgetController extends Controller
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

        return view('project-budgets.index', [
            'projects' => $projects,
        ]);
    }

    public function show(Project $project): View
    {
        $this->ensureSeeded($project);

        $items = ProjectBudgetItem::treeForProject($project->id);

        return view('project-budgets.show', [
            'project' => $project,
            'items' => $items,
            'grandTotal' => $items->sum(fn (ProjectBudgetItem $item) => $item->total),
        ]);
    }

    // The Project Budget is cloned from the project's Repair List (which is
    // itself seeded from the confirmed Quotation first), defaulting each
    // item's unit rate from the originating Quotation item.
    private function ensureSeeded(Project $project): void
    {
        if (ProjectBudgetItem::query()->forProject($project->id)->exists()) {
            return;
        }

        if (! RepairListItem::query()->forProject($project->id)->exists()) {
            $quotation = $project->confirmOrders()->latest()->first()?->quotation;

            if ($quotation) {
                RepairListItem::cloneFromQuotation($project->id, $quotation->id);
            }
        }

        ProjectBudgetItem::cloneFromRepairList($project->id);
    }
}
