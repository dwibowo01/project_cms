<?php

namespace App\Http\Requests\RepairListItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRepairListItemRequest extends FormRequest
{
    public function rules(): array
    {
        $projectId = $this->route('project')->id;

        return [
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('repair_list_items', 'id')
                    ->where('tenant_id', tenant('id'))
                    ->where('project_id', $projectId),
            ],
            'name' => ['required', 'string'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'pic_user_id' => [
                'nullable',
                'integer',
                // Any member of this project's team is eligible as PIC, whether
                // they hold the Project Manager Docking or Member Docking role.
                Rule::exists('project_teams', 'user_id')
                    ->where('tenant_id', tenant('id'))
                    ->where('project_id', $projectId),
            ],
        ];
    }
}
