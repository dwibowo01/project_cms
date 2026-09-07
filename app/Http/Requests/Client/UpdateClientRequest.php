<?php

namespace App\Http\Requests\Client;

use Illuminate\Validation\Rule;

class UpdateClientRequest extends StoreClientRequest
{
    public function rules(): array
    {
        $clientId = $this->route('client');

        return [
            ...parent::rules(),
            'client_email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('clients', 'client_email')->where('tenant_id', tenant('id'))->ignore($clientId),
            ],
        ];
    }
}
