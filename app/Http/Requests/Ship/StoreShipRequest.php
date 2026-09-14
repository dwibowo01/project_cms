<?php

namespace App\Http\Requests\Ship;

use App\Models\Ship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShipRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ship_name' => ['required', 'string', 'max:255'],
            'ship_type' => ['required', 'string', Rule::in(Ship::SHIP_TYPES)],
            'client_id' => [
                'required',
                'integer',
                Rule::exists('clients', 'id')->where('tenant_id', tenant('id')),
            ],
            'loa_value' => ['required', 'numeric', 'min:0'],
            'loa_unit' => ['required', 'string', Rule::in(Ship::UNITS)],
            'lbp_value' => ['nullable', 'numeric', 'min:0'],
            'lbp_unit' => ['nullable', 'required_with:lbp_value', 'string', Rule::in(Ship::UNITS)],
            'height_value' => ['nullable', 'numeric', 'min:0'],
            'height_unit' => ['nullable', 'required_with:height_value', 'string', Rule::in(Ship::UNITS)],
            'width_value' => ['nullable', 'numeric', 'min:0'],
            'width_unit' => ['nullable', 'required_with:width_value', 'string', Rule::in(Ship::UNITS)],
            'draught_value' => ['nullable', 'numeric', 'min:0'],
            'draught_unit' => ['nullable', 'required_with:draught_value', 'string', Rule::in(Ship::UNITS)],
            'gt' => ['nullable', 'numeric', 'min:0'],
            'nt' => ['nullable', 'numeric', 'min:0'],
            'power_me' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
