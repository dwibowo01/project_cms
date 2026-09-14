<?php

namespace App\Http\Requests\QuotationItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuotationItemRequest extends FormRequest
{
    public function rules(): array
    {
        $quotationId = $this->route('quotation')->id;

        return [
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('quotation_items', 'id')
                    ->where('tenant_id', tenant('id'))
                    ->where('quotation_id', $quotationId),
            ],
            'name' => ['required', 'string'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
