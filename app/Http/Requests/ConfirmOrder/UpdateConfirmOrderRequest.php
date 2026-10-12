<?php

namespace App\Http\Requests\ConfirmOrder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfirmOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'docking_start_date' => ['required', 'date'],
            'ppn' => ['required', 'boolean'],
        ];
    }
}
