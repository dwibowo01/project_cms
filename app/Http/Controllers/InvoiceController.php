<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\ConfirmOrder;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\RepairListItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $invoices = Invoice::query()
            ->with(['confirmOrder.project', 'confirmOrder.quotation.client'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('invoice_no', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', [
            'invoices' => $invoices,
            'statuses' => InvoiceStatus::cases(),
        ]);
    }

    // Step 1 (no confirm_order_id yet): pick which Confirm Order this invoice bills
    // against. Step 2 (confirm_order_id present): the full form, with the Scope of
    // Work/progress table and deduction preview resolved for that Confirm Order.
    public function create(Request $request): View
    {
        $confirmOrder = null;

        if ($request->filled('confirm_order_id')) {
            $confirmOrder = ConfirmOrder::with(['quotation.ship', 'quotation.client', 'quotation.clientContact', 'quotation.revisions', 'project'])
                ->find($request->integer('confirm_order_id'));
        }

        return view('invoices.create', [
            'confirmOrders' => ConfirmOrder::with(['quotation.ship', 'quotation.client', 'project'])->latest()->get(),
            'confirmOrder' => $confirmOrder,
            'scopeOfWork' => $confirmOrder ? $this->scopeOfWorkFor($confirmOrder->project) : collect(),
            'types' => InvoiceType::cases(),
            'paidDownPayment' => $confirmOrder ? $this->paidAmount($confirmOrder->project_id, [InvoiceType::DOWN_PAYMENT]) : 0.0,
            'paidDownPaymentAndProgress' => $confirmOrder ? $this->paidAmount($confirmOrder->project_id, [InvoiceType::DOWN_PAYMENT, InvoiceType::PROGRESS_PAYMENT]) : 0.0,
        ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $confirmOrder = ConfirmOrder::findOrFail($data['confirm_order_id']);
        $type = InvoiceType::from($data['invoice_type']);

        $invoice = DB::transaction(function () use ($data, $confirmOrder, $type, $request) {
            $computed = $this->computeAmounts($confirmOrder, $type, $data);

            $invoice = Invoice::create([
                'invoice_date' => $data['invoice_date'],
                'invoice_type' => $type->value,
                'confirm_order_id' => $confirmOrder->id,
                'gross_amount' => $computed['gross_amount'],
                'deduction_amount' => $computed['deduction_amount'],
                'amount' => $computed['amount'],
                'discount_percentage' => $computed['discount_percentage'],
                'discount_amount' => $computed['discount_amount'],
                'taxable_amount' => $computed['taxable_amount'],
                'ppn_percentage' => $computed['ppn_percentage'],
                'ppn_amount' => $computed['ppn_amount'],
                'total_amount' => $computed['total_amount'],
                'created_by' => $request->user()->id,
            ]);

            $this->syncItems($invoice, $computed['items']);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', __('Invoice created successfully.'));
    }

    public function show(Invoice $invoice): View
    {
        return view('invoices.show', [
            'invoice' => $invoice->load([
                'confirmOrder.project',
                'confirmOrder.quotation.ship',
                'confirmOrder.quotation.client',
                'confirmOrder.quotation.clientContact',
                'confirmOrder.quotation.revisions',
                'items',
                'creator',
            ]),
        ]);
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load(['confirmOrder.quotation.ship', 'confirmOrder.quotation.client', 'confirmOrder.quotation.revisions', 'confirmOrder.project', 'items']);
        $confirmOrder = $invoice->confirmOrder;

        return view('invoices.edit', [
            'invoice' => $invoice,
            'confirmOrder' => $confirmOrder,
            'scopeOfWork' => $this->scopeOfWorkFor($confirmOrder->project),
            'paidDownPayment' => $this->paidAmount($confirmOrder->project_id, [InvoiceType::DOWN_PAYMENT], $invoice->id),
            'paidDownPaymentAndProgress' => $this->paidAmount($confirmOrder->project_id, [InvoiceType::DOWN_PAYMENT, InvoiceType::PROGRESS_PAYMENT], $invoice->id),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validated();
        $confirmOrder = $invoice->confirmOrder;
        $type = $invoice->invoice_type;

        DB::transaction(function () use ($data, $invoice, $confirmOrder, $type) {
            $computed = $this->computeAmounts($confirmOrder, $type, $data, excludeInvoiceId: $invoice->id);

            $invoice->update([
                'invoice_date' => $data['invoice_date'],
                'status' => $data['status'],
                'gross_amount' => $computed['gross_amount'],
                'deduction_amount' => $computed['deduction_amount'],
                'amount' => $computed['amount'],
                'discount_percentage' => $computed['discount_percentage'],
                'discount_amount' => $computed['discount_amount'],
                'taxable_amount' => $computed['taxable_amount'],
                'ppn_percentage' => $computed['ppn_percentage'],
                'ppn_amount' => $computed['ppn_amount'],
                'total_amount' => $computed['total_amount'],
            ]);

            $invoice->items()->delete();
            $this->syncItems($invoice, $computed['items']);
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', __('Invoice updated successfully.'));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', __('Invoice deleted successfully.'));
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $index => $item) {
            $invoice->items()->create([
                'repair_list_item_id' => $item['repair_list_item_id'],
                'name' => $item['name'],
                'fee' => $item['fee'],
                'progress_percentage' => $item['progress'],
                'discount_percentage' => $item['discount'],
                'amount' => $item['amount'],
                'sort_order' => $index + 1,
            ]);
        }
    }

    /**
     * The Repair List's root-level items for a project, seeding the Repair List
     * from the project's confirmed Quotation first if it hasn't been visited yet.
     * This is what Invoice's Scope of Work is billed against — structure from the
     * Repair List, pricing from the original (unedited) Quotation unit rate.
     */
    private function scopeOfWorkFor(Project $project): Collection
    {
        $this->ensureRepairListSeeded($project);

        return RepairListItem::treeForProject($project->id);
    }

    private function ensureRepairListSeeded(Project $project): void
    {
        if (RepairListItem::query()->forProject($project->id)->exists()) {
            return;
        }

        $quotation = $project->confirmOrders()->latest()->first()?->quotation;

        if ($quotation) {
            RepairListItem::cloneFromQuotation($project->id, $quotation->id);
        }
    }

    /**
     * Recomputes every monetary figure for an invoice from scratch — never trusts
     * client-submitted totals — so tampering with hidden/computed fields can't
     * affect what actually gets persisted.
     */
    private function computeAmounts(ConfirmOrder $confirmOrder, InvoiceType $type, array $data, ?int $excludeInvoiceId = null): array
    {
        if (! $type->requiresScopeOfWork()) {
            $amount = round((float) $data['amount'], 2);

            return [
                'gross_amount' => null,
                'deduction_amount' => 0.0,
                'amount' => $amount,
                'discount_percentage' => null,
                'discount_amount' => 0.0,
                'taxable_amount' => $amount,
                'ppn_percentage' => null,
                'ppn_amount' => 0.0,
                'total_amount' => $amount,
                'items' => [],
            ];
        }

        $submittedByItemId = collect($data['items'] ?? [])->keyBy(fn ($item) => (int) $item['repair_list_item_id']);

        $items = $this->scopeOfWorkFor($confirmOrder->project)->map(function (RepairListItem $node) use ($submittedByItemId) {
            $fee = (float) $node->quotation_total;
            $submitted = $submittedByItemId->get($node->id);
            $progress = (float) ($submitted['progress'] ?? 0);
            $discount = (float) ($submitted['discount'] ?? 0);
            $amount = round($fee * $progress / 100 * (1 - $discount / 100), 2);

            return [
                'repair_list_item_id' => $node->id,
                'name' => $node->name,
                'fee' => $fee,
                'progress' => $progress,
                'discount' => $discount,
                'amount' => $amount,
            ];
        })->values()->all();

        $grossAmount = round(array_sum(array_column($items, 'amount')), 2);

        $deductionTypes = match ($type) {
            InvoiceType::PROGRESS_PAYMENT => [InvoiceType::DOWN_PAYMENT],
            InvoiceType::FINAL_PAYMENT => [InvoiceType::DOWN_PAYMENT, InvoiceType::PROGRESS_PAYMENT],
            default => [],
        };

        $deductionAmount = $deductionTypes === []
            ? 0.0
            : $this->paidAmount($confirmOrder->project_id, $deductionTypes, $excludeInvoiceId);

        $amount = max($grossAmount - $deductionAmount, 0.0);

        // Header-level discount, applied to "amount" (Jumlah Invoice) before PPN.
        $discountPercentage = round((float) ($data['discount_percentage'] ?? 0), 2);
        $discountAmount = round($amount * $discountPercentage / 100, 2);
        $taxableAmount = round($amount - $discountAmount, 2);

        $ppnPercentage = round((float) $data['ppn_percentage'], 2);
        $ppnAmount = round($taxableAmount * $ppnPercentage / 100, 2);

        return [
            'gross_amount' => $grossAmount,
            'deduction_amount' => $deductionAmount,
            'amount' => $amount,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'taxable_amount' => $taxableAmount,
            'ppn_percentage' => $ppnPercentage,
            'ppn_amount' => $ppnAmount,
            'total_amount' => round($taxableAmount + $ppnAmount, 2),
            'items' => $items,
        ];
    }

    /**
     * Sum of "amount" (net, post-deduction) across Paid invoices of the given
     * type(s) for a project — the basis for Progress/Final payment deductions.
     */
    private function paidAmount(int $projectId, array $types, ?int $excludeInvoiceId = null): float
    {
        return (float) Invoice::query()
            ->forProject($projectId)
            ->whereIn('invoice_type', array_map(fn (InvoiceType $t) => $t->value, $types))
            ->where('status', InvoiceStatus::PAID->value)
            ->when($excludeInvoiceId, fn ($query) => $query->where('id', '!=', $excludeInvoiceId))
            ->sum('amount');
    }
}
