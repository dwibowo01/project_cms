<?php

namespace App\Http\Requests\ProjectBudgetItem;

use App\Models\ProjectBudgetItem;

class UpdateProjectBudgetItemRequest extends StoreProjectBudgetItemRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['parent_id'][] = function ($attribute, $value, $fail): void {
            if (! $value) {
                return;
            }

            /** @var ProjectBudgetItem $item */
            $item = $this->route('item');

            if ((int) $value === $item->id) {
                $fail(__('An item cannot be its own parent.'));

                return;
            }

            if ($this->isDescendant($item, (int) $value)) {
                $fail(__("The selected parent cannot be one of this item's own sub-items."));
            }
        };

        return $rules;
    }

    // Walks the subtree to prevent a parent/child cycle.
    private function isDescendant(ProjectBudgetItem $item, int $candidateParentId): bool
    {
        $childIds = ProjectBudgetItem::query()->where('parent_id', $item->id)->pluck('id');

        foreach ($childIds as $childId) {
            if ($childId === $candidateParentId) {
                return true;
            }

            if ($this->isDescendant(ProjectBudgetItem::find($childId), $candidateParentId)) {
                return true;
            }
        }

        return false;
    }
}
