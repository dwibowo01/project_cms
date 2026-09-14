<?php

namespace App\Http\Requests\MasterItem;

use App\Models\MasterItem;

class UpdateMasterItemRequest extends StoreMasterItemRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['parent_id'][] = function ($attribute, $value, $fail): void {
            if (! $value) {
                return;
            }

            /** @var MasterItem $item */
            $item = $this->route('master_item');

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
    private function isDescendant(MasterItem $item, int $candidateParentId): bool
    {
        $childIds = MasterItem::query()->where('parent_id', $item->id)->pluck('id');

        foreach ($childIds as $childId) {
            if ($childId === $candidateParentId) {
                return true;
            }

            if ($this->isDescendant(MasterItem::find($childId), $candidateParentId)) {
                return true;
            }
        }

        return false;
    }
}
