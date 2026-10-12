<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrder\StoreConfirmOrderRequest;
use App\Http\Requests\ConfirmOrder\UpdateConfirmOrderRequest;
use App\Models\ConfirmOrder;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectBudgetItem;
use App\Models\Quotation;
use App\Models\RepairListItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConfirmOrderController extends Controller
{
    public function index(Request $request): View
    {
        $confirmOrders = ConfirmOrder::query()
            ->with(['quotation.ship', 'quotation.client', 'project'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('confirm_order_no', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('confirm-orders.index', [
            'confirmOrders' => $confirmOrders,
        ]);
    }

    public function create(): View
    {
        return view('confirm-orders.create', [
            'quotations' => Quotation::with(['ship', 'client'])->latest()->get(),
        ]);
    }

    // Creating a Confirm Order also creates its Project — named "[Docking Project
    // Type] [Ship Name]" (e.g. "DOCKING [Ship Name]") — in the same transaction,
    // since every Confirm Order owns exactly one Project. The docking project
    // type itself is chosen on the Quotation, not here. The Project's Repair
    // List and Project Budget are seeded immediately (instead of waiting for
    // their pages' lazy first-visit seeding), and a Down Payment Invoice is
    // created from the submitted down_payment_amount. The Project Team is left
    // empty — it's already selectable from the Project Teams page right away.
    public function store(StoreConfirmOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $quotation = Quotation::with('ship')->findOrFail($data['quotation_id']);

        $confirmOrder = DB::transaction(function () use ($data, $quotation, $request) {
            $project = Project::create([
                'project_name' => $quotation->docking_project_type->value.' '.$quotation->ship->ship_name,
                'client_id' => $quotation->client_id,
                'docking_start_date' => $data['docking_start_date'],
            ]);

            $confirmOrder = ConfirmOrder::create([
                'quotation_id' => $quotation->id,
                'project_id' => $project->id,
                'docking_start_date' => $data['docking_start_date'],
                'ppn' => $data['ppn'],
                'down_payment_amount' => $data['down_payment_amount'],
                'created_by' => $request->user()->id,
            ]);

            RepairListItem::cloneFromQuotation($project->id, $quotation->id);
            ProjectBudgetItem::cloneFromRepairList($project->id);

            Invoice::createDownPayment($confirmOrder, (float) $data['down_payment_amount'], $request->user()->id);

            return $confirmOrder;
        });

        return redirect()->route('confirm-orders.show', $confirmOrder)
            ->with('success', __('Confirm order created successfully.'));
    }

    public function show(ConfirmOrder $confirmOrder): View
    {
        return view('confirm-orders.show', [
            'confirmOrder' => $confirmOrder->load(['quotation.ship', 'quotation.client', 'quotation.clientContact', 'project', 'creator']),
        ]);
    }

    public function edit(ConfirmOrder $confirmOrder): View
    {
        return view('confirm-orders.edit', [
            'confirmOrder' => $confirmOrder->load(['quotation.ship', 'quotation.client', 'project']),
        ]);
    }

    public function update(UpdateConfirmOrderRequest $request, ConfirmOrder $confirmOrder): RedirectResponse
    {
        $data = $request->validated();

        $confirmOrder->update($data);
        $confirmOrder->project->update(['docking_start_date' => $data['docking_start_date']]);

        return redirect()->route('confirm-orders.show', $confirmOrder)
            ->with('success', __('Confirm order updated successfully.'));
    }

    public function destroy(ConfirmOrder $confirmOrder): RedirectResponse
    {
        $confirmOrder->delete();

        return redirect()->route('confirm-orders.index')
            ->with('success', __('Confirm order deleted successfully.'));
    }

    // Renders the full printable form (Order Confirmation, Scope of Work, and
    // Syarat dan Ketentuan Umum) as a PDF, mirroring public/Form/FORM CONFIRM ORDER.docx.
    public function export(ConfirmOrder $confirmOrder): Response
    {
        $confirmOrder->load(['quotation.ship', 'quotation.client', 'quotation.clientContact', 'project', 'creator']);

        $pdf = Pdf::loadView('confirm-orders.pdf', [
            'confirmOrder' => $confirmOrder,
            'logoDataUri' => 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('logo_caputra.png'))),
            'notes' => config('confirm_order.notes'),
            'paymentTerms' => $this->resolvePaymentTerms($confirmOrder),
            'terms' => config('confirm_order.terms'),
        ])->setPaper('a4');

        $filename = 'Confirm-Order-'.str_replace('/', '-', $confirmOrder->confirm_order_no).'.pdf';

        return $pdf->download($filename);
    }

    // Fills in the ":amount"/":terbilang" placeholders in the configured payment
    // terms template with this Confirm Order's actual down payment figure, e.g.
    // "...Rp. 200.000.000 (dua ratus juta rupiah)...".
    private function resolvePaymentTerms(ConfirmOrder $confirmOrder): array
    {
        $amount = number_format((float) $confirmOrder->down_payment_amount, 0, ',', '.');
        $terbilang = $confirmOrder->down_payment_terbilang;

        return array_map(
            fn (string $term) => str_replace([':amount', ':terbilang'], [$amount, $terbilang], $term),
            config('confirm_order.payment_terms')
        );
    }
}
