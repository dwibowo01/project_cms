<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationItem\StoreQuotationItemRequest;
use App\Http\Requests\QuotationItem\UpdateQuotationItemRequest;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class QuotationItemController extends Controller
{
    public function create(Request $request, Quotation $quotation): View
    {
        $parent = $request->filled('parent_id') ? QuotationItem::find($request->query('parent_id')) : null;

        return view('quotations.items.create', [
            'quotation' => $quotation,
            'parent' => $parent,
            'parentOptions' => $this->parentOptions($quotation->id),
        ]);
    }

    public function store(StoreQuotationItemRequest $request, Quotation $quotation): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $this->nextSortOrder($quotation->id, $data['parent_id'] ?? null);

        $quotation->items()->create($data);

        return redirect()->route('quotations.show', $quotation)
            ->with('success', __('Item created successfully.'));
    }

    public function edit(Quotation $quotation, QuotationItem $item): View
    {
        return view('quotations.items.edit', [
            'quotation' => $quotation,
            'item' => $item,
            'parentOptions' => $this->parentOptions($quotation->id, $item),
        ]);
    }

    public function update(UpdateQuotationItemRequest $request, Quotation $quotation, QuotationItem $item): RedirectResponse
    {
        $item->update($request->validated());

        return redirect()->route('quotations.show', $quotation)
            ->with('success', __('Item updated successfully.'));
    }

    public function destroy(Quotation $quotation, QuotationItem $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('quotations.show', $quotation)
            ->with('success', __('Item deleted successfully.'));
    }

    private function nextSortOrder(int $quotationId, ?int $parentId): int
    {
        $max = QuotationItem::query()
            ->forQuotation($quotationId)
            ->when($parentId === null, fn ($q) => $q->whereNull('parent_id'), fn ($q) => $q->where('parent_id', $parentId))
            ->max('sort_order');

        return (int) $max + 1;
    }

    // Flattened, depth-ordered options for the parent <select>; excludes a node and its own subtree.
    private function parentOptions(int $quotationId, ?QuotationItem $excluding = null): Collection
    {
        $options = collect();

        $flatten = function (Collection $nodes) use (&$flatten, &$options, $excluding): void {
            foreach ($nodes as $node) {
                if ($excluding && $node->id === $excluding->id) {
                    continue;
                }

                $options->push($node);
                $flatten($node->children);
            }
        };

        $flatten(QuotationItem::treeForQuotation($quotationId));

        return $options;
    }
}
