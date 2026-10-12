<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectBudgetItem\StoreProjectBudgetItemRequest;
use App\Http\Requests\ProjectBudgetItem\UpdateProjectBudgetItemRequest;
use App\Models\Project;
use App\Models\ProjectBudgetItem;
use Illuminate\Http\JsonResponse;

class ProjectBudgetItemController extends Controller
{
    public function store(StoreProjectBudgetItemRequest $request, Project $project): JsonResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $this->nextSortOrder($project->id, $data['parent_id'] ?? null);

        $project->projectBudgetItems()->create($data);

        return $this->sectionResponse($project);
    }

    public function update(UpdateProjectBudgetItemRequest $request, Project $project, ProjectBudgetItem $item): JsonResponse
    {
        $item->update($request->validated());

        return $this->sectionResponse($project);
    }

    public function destroy(Project $project, ProjectBudgetItem $item): JsonResponse
    {
        $item->delete();

        return $this->sectionResponse($project);
    }

    private function nextSortOrder(int $projectId, ?int $parentId): int
    {
        $max = ProjectBudgetItem::query()
            ->forProject($projectId)
            ->when($parentId === null, fn ($q) => $q->whereNull('parent_id'), fn ($q) => $q->where('parent_id', $parentId))
            ->max('sort_order');

        return (int) $max + 1;
    }

    // Re-renders the whole items table so the server stays the single source of truth for outline codes/totals.
    private function sectionResponse(Project $project): JsonResponse
    {
        $items = ProjectBudgetItem::treeForProject($project->id);

        $html = view('project-budgets.partials.items-section', [
            'project' => $project,
            'items' => $items,
            'grandTotal' => $items->sum(fn (ProjectBudgetItem $item) => $item->total),
        ])->render();

        return response()->json(['html' => $html]);
    }
}
