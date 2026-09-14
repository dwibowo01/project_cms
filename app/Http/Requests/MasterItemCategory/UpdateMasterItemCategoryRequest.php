<?php

namespace App\Http\Requests\MasterItemCategory;

use Illuminate\Validation\Rule;

class UpdateMasterItemCategoryRequest extends StoreMasterItemCategoryRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('master_item_categories', 'name')
                    ->where('tenant_id', tenant('id'))
                    ->ignore($this->route('master_item_category')),
            ],
        ];
    }
}
