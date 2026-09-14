<?php

namespace App\Http\Requests\MasterItem;

use App\Models\MasterItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMasterItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                Rule::exists('master_item_categories', 'id')->where('tenant_id', tenant('id')),
            ],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('master_items', 'id')->where('tenant_id', tenant('id')),
                function ($attribute, $value, $fail): void {
                    if (! $value) {
                        return;
                    }

                    $parent = MasterItem::find($value);

                    if ($parent && $parent->category_id !== (int) $this->input('category_id')) {
                        $fail(__('The selected parent must belong to the same category.'));
                    }
                },
            ],
            'name' => ['required', 'string'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
