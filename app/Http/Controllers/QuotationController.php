<?php

namespace App\Http\Controllers;

use App\Exports\QuotationSpreadsheetExporter;
use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Http\Requests\Quotation\UpdateQuotationRequest;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\MasterItem;
use App\Models\MasterItemCategory;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Ship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        $quotations = Quotation::query()
            ->with(['client', 'ship'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('quote_no', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('quotations.index', [
            'quotations' => $quotations,
        ]);
    }

    public function create(Request $request): View
    {
        $mode = in_array($request->query('mode'), ['scratch', 'master-item', 'duplicate'], true)
            ? $request->query('mode')
            : 'scratch';

        return view('quotations.create', [
            'mode' => $mode,
            'clients' => Client::orderBy('client_name')->get(),
            'ships' => Ship::orderBy('ship_name')->get(),
            'clientContacts' => ClientContact::orderBy('name')->get(),
            'categories' => MasterItemCategory::orderBy('name')->get(),
            'sourceQuotations' => Quotation::with('ship')->latest()->get(),
        ]);
    }

    public function store(StoreQuotationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $mode = $data['mode'];

        $quotation = Quotation::create([
            ...collect($data)->only([
                'client_id', 'client_contact_id', 'ship_id',
                'docking_year', 'survey_type', 'quotation_date',
            ])->all(),
            'master_item_category_id' => $mode === 'master-item' ? $data['master_item_category_id'] : null,
            'source_quotation_id' => $mode === 'duplicate' ? $data['source_quotation_id'] : null,
            'created_by' => $request->user()->id,
        ]);

        match ($mode) {
            'master-item' => $this->copyFromMasterItemCategory($quotation, (int) $data['master_item_category_id']),
            'duplicate' => $this->copyFromQuotation($quotation, (int) $data['source_quotation_id']),
            default => null,
        };

        return redirect()->route('quotations.show', $quotation)
            ->with('success', __('Quotation created successfully.'));
    }

    public function show(Quotation $quotation): View
    {
        return view('quotations.show', [
            'quotation' => $quotation->load(['client', 'clientContact', 'ship', 'creator']),
            'items' => QuotationItem::treeForQuotation($quotation->id),
        ]);
    }

    public function edit(Quotation $quotation): View
    {
        return view('quotations.edit', [
            'quotation' => $quotation,
            'clients' => Client::orderBy('client_name')->get(),
            'ships' => Ship::orderBy('ship_name')->get(),
            'clientContacts' => ClientContact::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation): RedirectResponse
    {
        $quotation->update($request->validated());

        return redirect()->route('quotations.show', $quotation)
            ->with('success', __('Quotation updated successfully.'));
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', __('Quotation deleted successfully.'));
    }

    // Clones the quotation's header + items into a new row with the same quote_no
    // and the next revision number, leaving this row untouched as history.
    public function revise(Request $request, Quotation $quotation): RedirectResponse
    {
        $nextRevision = (int) Quotation::where('quote_no', $quotation->quote_no)->max('revision') + 1;

        $newQuotation = Quotation::create([
            'quote_no' => $quotation->quote_no,
            'revision' => $nextRevision,
            'client_id' => $quotation->client_id,
            'client_contact_id' => $quotation->client_contact_id,
            'ship_id' => $quotation->ship_id,
            'master_item_category_id' => $quotation->master_item_category_id,
            'source_quotation_id' => $quotation->id,
            'created_by' => $request->user()->id,
            'docking_year' => $quotation->docking_year,
            'survey_type' => $quotation->survey_type,
            'quotation_date' => now()->toDateString(),
        ]);

        $this->copyFromQuotation($newQuotation, $quotation->id);

        return redirect()->route('quotations.show', $newQuotation)
            ->with('success', __('New revision created successfully.'));
    }

    public function export(Quotation $quotation): StreamedResponse
    {
        $spreadsheet = QuotationSpreadsheetExporter::make($quotation);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Penawaran-'.str_replace('/', '-', $quotation->quote_no).'.xlsx';

        return response()->streamDownload(fn () => $writer->save('php://output'), $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function copyFromMasterItemCategory(Quotation $quotation, int $categoryId): void
    {
        $this->cloneNodes(MasterItem::treeForCategory($categoryId), $quotation->id, null, isMasterItem: true);
    }

    private function copyFromQuotation(Quotation $quotation, int $sourceQuotationId): void
    {
        $this->cloneNodes(QuotationItem::treeForQuotation($sourceQuotationId), $quotation->id, null, isMasterItem: false);
    }

    // Recursively clones a MasterItem or QuotationItem tree into the new quotation's items.
    private function cloneNodes(Collection $nodes, int $quotationId, ?int $parentId, bool $isMasterItem): void
    {
        foreach ($nodes as $index => $node) {
            $item = QuotationItem::create([
                'quotation_id' => $quotationId,
                'master_item_id' => $isMasterItem ? $node->id : $node->master_item_id,
                'parent_id' => $parentId,
                'name' => $node->name,
                'qty' => $node->qty,
                'unit' => $node->unit,
                'unit_price' => $node->unit_price,
                'sort_order' => $index + 1,
            ]);

            $this->cloneNodes($node->children, $quotationId, $item->id, $isMasterItem);
        }
    }
}
