<?php

namespace App\Http\Requests\ClientContact;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
        ];
    }
}
