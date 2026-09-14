<?php

namespace App\Http\Controllers;

use App\Http\Requests\MasterItemCategory\StoreMasterItemCategoryRequest;
use App\Http\Requests\MasterItemCategory\UpdateMasterItemCategoryRequest;
use App\Models\MasterItemCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MasterItemCategoryController extends Controller
{
    public function index(): View
    {
        return view('master-item-categories.index', [
            'categories' => MasterItemCategory::withCount('items')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreMasterItemCategoryRequest $request): RedirectResponse
    {
        MasterItemCategory::create($request->validated());

        return redirect()->route('master-item-categories.index')
            ->with('success', __('Category created successfully.'));
    }

    public function update(UpdateMasterItemCategoryRequest $request, MasterItemCategory $master_item_category): RedirectResponse
    {
        $master_item_category->update($request->validated());

        return redirect()->route('master-item-categories.index')
            ->with('success', __('Category updated successfully.'));
    }

    public function destroy(MasterItemCategory $master_item_category): RedirectResponse
    {
        $master_item_category->delete();

        return redirect()->route('master-item-categories.index')
            ->with('success', __('Category deleted successfully.'));
    }
}
