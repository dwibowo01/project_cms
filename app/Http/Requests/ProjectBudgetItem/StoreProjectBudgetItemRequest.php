<?php

namespace App\Http\Requests\ProjectBudgetItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectBudgetItemRequest extends FormRequest
{
    public function rules(): array
    {
        $projectId = $this->route('project')->id;

        return [
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('project_budget_items', 'id')
                    ->where('tenant_id', tenant('id'))
                    ->where('project_id', $projectId),
            ],
            'name' => ['required', 'string'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
