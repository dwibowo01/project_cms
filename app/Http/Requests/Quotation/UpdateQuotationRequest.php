<?php

namespace App\Http\Requests\Quotation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                'integer',
                Rule::exists('clients', 'id')->where('tenant_id', tenant('id')),
            ],
            'client_contact_id' => [
                'nullable',
                'integer',
                Rule::exists('client_contacts', 'id')->where('tenant_id', tenant('id'))->where('client_id', $this->input('client_id')),
            ],
            'ship_id' => [
                'required',
                'integer',
                Rule::exists('ships', 'id')->where('tenant_id', tenant('id'))->where('client_id', $this->input('client_id')),
            ],
            'docking_year' => ['required', 'digits:4'],
            'survey_type' => ['required', Rule::in(\App\Models\Quotation::SURVEY_TYPES)],
            'quotation_date' => ['required', 'date'],
            'revision' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
