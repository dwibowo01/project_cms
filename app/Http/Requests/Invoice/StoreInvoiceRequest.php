<?php

namespace App\Http\Requests\Invoice;

use App\Enums\InvoiceType;
use App\Models\ConfirmOrder;
use App\Models\RepairListItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        $requiresScopeOfWork = $this->input('invoice_type') !== InvoiceType::DOWN_PAYMENT->value;

        return [
            'confirm_order_id' => [
                'required',
                'integer',
                Rule::exists('confirm_orders', 'id')->where('tenant_id', tenant('id')),
            ],
            'invoice_date' => ['required', 'date'],
            'invoice_type' => ['required', new Enum(InvoiceType::class)],
            'amount' => [Rule::requiredIf(! $requiresScopeOfWork), 'nullable', 'numeric', 'min:0'],
            'discount_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'ppn_percentage' => [Rule::requiredIf($requiresScopeOfWork), 'nullable', 'numeric', 'between:0,100'],
            'items' => [Rule::requiredIf($requiresScopeOfWork), 'nullable', 'array'],
            'items.*.repair_list_item_id' => ['required_with:items', 'integer'],
            'items.*.progress' => ['required_with:items', 'numeric', 'between:0,100'],
            'items.*.discount' => ['nullable', 'numeric', 'between:0,100'],
        ];
    }

    // Cross-checks the submitted scope-of-work item IDs against the selected
    // Confirm Order's project's current Repair List root items, so progress
    // can't be attributed to an item that doesn't belong to it.
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $items = $this->input('items', []);

            if (! is_array($items) || $items === []) {
                return;
            }

            $confirmOrder = ConfirmOrder::find($this->input('confirm_order_id'));

            if (! $confirmOrder) {
                return;
            }

            $validIds = RepairListItem::treeForProject($confirmOrder->project_id)->pluck('id')->map(fn ($id) => (string) $id)->sort()->values()->all();
            $submittedIds = collect($items)->pluck('repair_list_item_id')->map(fn ($id) => (string) $id)->sort()->values()->all();

            if ($validIds !== $submittedIds) {
                $validator->errors()->add('items', __('The submitted scope of work items do not match the selected Confirm Order.'));
            }
        });
    }
}
