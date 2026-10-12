<?php

namespace App\Http\Requests\Invoice;

use App\Enums\InvoiceStatus;
use App\Models\ConfirmOrder;
use App\Models\Invoice;
use App\Models\RepairListItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

// The Confirm Order and Invoice Type are immutable once an invoice is created
// (mirroring how a Confirm Order's Quotation can't change after creation) —
// only the date, status, and the amount/PPN/progress figures can be revised.
class UpdateInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var Invoice $invoice */
        $invoice = $this->route('invoice');
        $requiresScopeOfWork = $invoice->invoice_type->requiresScopeOfWork();

        return [
            'invoice_date' => ['required', 'date'],
            'status' => ['required', new Enum(InvoiceStatus::class)],
            'amount' => [Rule::requiredIf(! $requiresScopeOfWork), 'nullable', 'numeric', 'min:0'],
            'discount_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'ppn_percentage' => [Rule::requiredIf($requiresScopeOfWork), 'nullable', 'numeric', 'between:0,100'],
            'items' => [Rule::requiredIf($requiresScopeOfWork), 'nullable', 'array'],
            'items.*.repair_list_item_id' => ['required_with:items', 'integer'],
            'items.*.progress' => ['required_with:items', 'numeric', 'between:0,100'],
            'items.*.discount' => ['nullable', 'numeric', 'between:0,100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $items = $this->input('items', []);

            if (! is_array($items) || $items === []) {
                return;
            }

            /** @var Invoice $invoice */
            $invoice = $this->route('invoice');
            $confirmOrder = ConfirmOrder::find($invoice->confirm_order_id);

            if (! $confirmOrder) {
                return;
            }

            $validIds = RepairListItem::treeForProject($confirmOrder->project_id)->pluck('id')->map(fn ($id) => (string) $id)->sort()->values()->all();
            $submittedIds = collect($items)->pluck('repair_list_item_id')->map(fn ($id) => (string) $id)->sort()->values()->all();

            if ($validIds !== $submittedIds) {
                $validator->errors()->add('items', __('The submitted scope of work items do not match this invoice\'s Confirm Order.'));
            }
        });
    }
}
