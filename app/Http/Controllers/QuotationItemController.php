<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationItem\StoreQuotationItemRequest;
use App\Http\Requests\QuotationItem\UpdateQuotationItemRequest;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\JsonResponse;

class QuotationItemController extends Controller
{
    public function store(StoreQuotationItemRequest $request, Quotation $quotation): JsonResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $this->nextSortOrder($quotation->id, $data['parent_id'] ?? null);

        $quotation->items()->create($data);

        return $this->itemsSectionResponse($quotation);
    }

    public function update(UpdateQuotationItemRequest $request, Quotation $quotation, QuotationItem $item): JsonResponse
    {
        $item->update($request->validated());

        return $this->itemsSectionResponse($quotation);
    }

    public function destroy(Quotation $quotation, QuotationItem $item): JsonResponse
    {
        $item->delete();

        return $this->itemsSectionResponse($quotation);
    }

    private function nextSortOrder(int $quotationId, ?int $parentId): int
    {
        $max = QuotationItem::query()
            ->forQuotation($quotationId)
            ->when($parentId === null, fn ($q) => $q->whereNull('parent_id'), fn ($q) => $q->where('parent_id', $parentId))
            ->max('sort_order');

        return (int) $max + 1;
    }

    // Re-renders the whole items table so the server stays the single source of truth for outline codes/totals.
    private function itemsSectionResponse(Quotation $quotation): JsonResponse
    {
        $quotation = $quotation->fresh();

        $html = view('quotations.partials.items-section', [
            'quotation' => $quotation,
            'items' => QuotationItem::treeForQuotation($quotation->id),
        ])->render();

        return response()->json(['html' => $html]);
    }
}
