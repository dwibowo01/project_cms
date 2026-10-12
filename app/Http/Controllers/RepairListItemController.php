<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepairListItem\StoreRepairListItemRequest;
use App\Http\Requests\RepairListItem\UpdateRepairListItemRequest;
use App\Models\Project;
use App\Models\RepairListItem;
use Illuminate\Http\JsonResponse;

class RepairListItemController extends Controller
{
    public function store(StoreRepairListItemRequest $request, Project $project): JsonResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $this->nextSortOrder($project->id, $data['parent_id'] ?? null);

        $project->repairListItems()->create($data);

        return $this->sectionResponse($project);
    }

    public function update(UpdateRepairListItemRequest $request, Project $project, RepairListItem $item): JsonResponse
    {
        $item->update($request->validated());

        // A parent/group node's PIC assignment (or removal) flows down to every descendant.
        if ($item->wasChanged('pic_user_id')) {
            $item->cascadePicToChildren();
        }

        return $this->sectionResponse($project);
    }

    public function destroy(Project $project, RepairListItem $item): JsonResponse
    {
        $item->delete();

        return $this->sectionResponse($project);
    }

    private function nextSortOrder(int $projectId, ?int $parentId): int
    {
        $max = RepairListItem::query()
            ->forProject($projectId)
            ->when($parentId === null, fn ($q) => $q->whereNull('parent_id'), fn ($q) => $q->where('parent_id', $parentId))
            ->max('sort_order');

        return (int) $max + 1;
    }

    // Re-renders the whole items table so the server stays the single source of truth for outline codes/PIC cascades.
    private function sectionResponse(Project $project): JsonResponse
    {
        $html = view('repair-lists.partials.items-section', [
            'project' => $project,
            'items' => RepairListItem::treeForProject($project->id),
            'picOptions' => $project->repairListPicOptions(),
        ])->render();

        return response()->json(['html' => $html]);
    }
}
