<?php

namespace App\Http\Requests\ProjectTeam;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Manages a project's whole team in one submission: a single optional
// Project Manager Docking plus any number of Member Docking.
class UpdateProjectTeamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'manager_id' => [
                'nullable',
                'integer',
                Rule::exists('tenant_user', 'user_id')->where('tenant_id', tenant('id')),
            ],
            'member_ids' => ['array'],
            'member_ids.*' => [
                'integer',
                Rule::exists('tenant_user', 'user_id')->where('tenant_id', tenant('id')),
                Rule::notIn(array_filter([$this->input('manager_id')])),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'member_ids.*.not_in' => __('A member already assigned as Project Manager Docking cannot also be a Member Docking.'),
        ];
    }
}
