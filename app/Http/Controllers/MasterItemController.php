<?php

namespace App\Http\Controllers;

use App\Http\Requests\MasterItem\StoreMasterItemRequest;
use App\Http\Requests\MasterItem\UpdateMasterItemRequest;
use App\Models\MasterItem;
use App\Models\MasterItemCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MasterItemController extends Controller
{
    public function index(Request $request): View
    {
        $categories = MasterItemCategory::orderBy('name')->get();
        $categoryId = $this->resolveCategoryId($request, $categories);

        return view('master-items.index', [
            'categoryId' => $categoryId,
            'categories' => $categories,
            'items' => $categoryId ? MasterItem::treeForCategory($categoryId) : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        $categories = MasterItemCategory::orderBy('name')->get();
        $parent = $request->filled('parent_id') ? MasterItem::find($request->query('parent_id')) : null;
        $categoryId = $parent->category_id ?? $this->resolveCategoryId($request, $categories);

        return view('master-items.create', [
            'categories' => $categories,
            'categoryId' => $categoryId,
            'parent' => $parent,
            'parentOptions' => $categoryId ? $this->parentOptions($categoryId) : collect(),
        ]);
    }

    public function store(StoreMasterItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $this->nextSortOrder((int) $data['category_id'], $data['parent_id'] ?? null);

        MasterItem::create($data);

        return redirect()->route('master-items.index', ['category_id' => $data['category_id']])
            ->with('success', __('Item created successfully.'));
    }

    public function edit(MasterItem $master_item): View
    {
        return view('master-items.edit', [
            'categories' => MasterItemCategory::orderBy('name')->get(),
            'categoryId' => $master_item->category_id,
            'item' => $master_item,
            'parentOptions' => $this->parentOptions($master_item->category_id, $master_item),
        ]);
    }

    public function update(UpdateMasterItemRequest $request, MasterItem $master_item): RedirectResponse
    {
        $master_item->update($request->validated());

        return redirect()->route('master-items.index', ['category_id' => $master_item->category_id])
            ->with('success', __('Item updated successfully.'));
    }

    public function destroy(MasterItem $master_item): RedirectResponse
    {
        $categoryId = $master_item->category_id;
        $master_item->delete();

        return redirect()->route('master-items.index', ['category_id' => $categoryId])
            ->with('success', __('Item deleted successfully.'));
    }

    private function resolveCategoryId(Request $request, Collection $categories): ?int
    {
        $requested = (int) $request->query('category_id');

        if ($requested && $categories->contains('id', $requested)) {
            return $requested;
        }

        return $categories->first()?->id;
    }

    private function nextSortOrder(int $categoryId, ?int $parentId): int
    {
        $max = MasterItem::query()
            ->forCategory($categoryId)
            ->when($parentId === null, fn ($q) => $q->whereNull('parent_id'), fn ($q) => $q->where('parent_id', $parentId))
            ->max('sort_order');

        return (int) $max + 1;
    }

    // Flattened, depth-ordered options for the parent <select>; excludes a node and its own subtree.
    private function parentOptions(int $categoryId, ?MasterItem $excluding = null): Collection
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

        $flatten(MasterItem::treeForCategory($categoryId));

        return $options;
    }
}
