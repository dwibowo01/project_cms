<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:255'],
            'client_id' => [
                'required',
                'integer',
                Rule::exists('clients', 'id')->where('tenant_id', tenant('id')),
            ],
            'description' => ['nullable', 'string'],
            'docking_start_date' => ['required', 'date'],
            'docking_end_date' => ['nullable', 'date', 'after_or_equal:docking_start_date'],
            'status' => ['required', new Enum(ProjectStatus::class)],
            'project_type' => ['required', new Enum(ProjectType::class)],
        ];
    }
}
