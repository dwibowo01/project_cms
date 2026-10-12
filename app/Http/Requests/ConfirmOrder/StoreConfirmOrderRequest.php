<?php

namespace App\Http\Requests\ConfirmOrder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConfirmOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quotation_id' => [
                'required',
                'integer',
                Rule::exists('quotations', 'id')->where('tenant_id', tenant('id')),
            ],
            'docking_start_date' => ['required', 'date'],
            'ppn' => ['required', 'boolean'],
            'down_payment_amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
