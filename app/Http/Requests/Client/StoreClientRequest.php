<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'string', 'max:255'],
            'client_company_type' => ['required', 'string', Rule::in(Client::COMPANY_TYPES)],
            'client_name' => ['nullable', 'string', 'max:255'],
            'client_phone_country_code' => ['nullable', 'string', 'max:10'],
            'client_phone_number' => ['nullable', 'string', 'max:50'],
            'client_email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('clients', 'client_email')->where('tenant_id', tenant('id')),
            ],
            'company_address_line_1' => ['nullable', 'string', 'max:255'],
            'company_address_line_2' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
