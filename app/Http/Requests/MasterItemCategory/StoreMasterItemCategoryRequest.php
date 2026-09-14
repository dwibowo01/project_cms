<?php

namespace App\Http\Requests\MasterItemCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMasterItemCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('master_item_categories', 'name')->where('tenant_id', tenant('id')),
            ],
        ];
    }
}
