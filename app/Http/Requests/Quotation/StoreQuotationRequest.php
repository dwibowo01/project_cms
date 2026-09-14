<?php

namespace App\Http\Requests\Quotation;

use App\Models\Quotation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mode' => ['required', Rule::in(['scratch', 'master-item', 'duplicate'])],
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
            'survey_type' => ['required', Rule::in(Quotation::SURVEY_TYPES)],
            'quotation_date' => ['required', 'date'],
            'master_item_category_id' => [
                'required_if:mode,master-item',
                'nullable',
                'integer',
                Rule::exists('master_item_categories', 'id')->where('tenant_id', tenant('id')),
            ],
            'source_quotation_id' => [
                'required_if:mode,duplicate',
                'nullable',
                'integer',
                Rule::exists('quotations', 'id')->where('tenant_id', tenant('id')),
            ],
        ];
    }
}
