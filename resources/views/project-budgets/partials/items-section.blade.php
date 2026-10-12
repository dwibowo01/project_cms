@php
    // Flatten the item tree so every row (including nested children) gets a default draft
    // entry up front — the edit-mode cells are always present in the DOM (just hidden via
    // x-show), so their `drafts[id].field` bindings would otherwise read undefined and throw.
    $flattenItems = function ($nodes) use (&$flattenItems) {
        $flat = collect();
        foreach ($nodes as $node) {
            $flat->push($node);
            $flat = $flat->merge($flattenItems($node->children));
        }

        return $flat;
    };
    $initialDrafts = $flattenItems($items)->mapWithKeys(
        fn($node) => [
            $node->id => [
                'name' => $node->name,
                'qty' => $node->qty,
                'unit' => $node->unit,
                'unit_price' => $node->unit_price,
                'percentage' => $node->percentage,
            ],
        ],
    );
@endphp

<div id="project-budget-items-section" class="overflow-x-auto" x-data="{
    editingIds: [],
    drafts: @js($initialDrafts),
    errors: {},
    saving: false,
    isEditing(id) { return this.editingIds.includes(id); },
    startEdit(id, initial) {
        if (!this.editingIds.includes(id)) this.editingIds.push(id);
        this.drafts[id] = { ...initial };
        this.errors[id] = {};
    },
    cancelAll() {
        this.editingIds = [];
    },
    async saveAll() {
        if (this.saving || this.editingIds.length === 0) return;
        this.saving = true;
        let lastHtml = null;
        const failedIds = [];
        for (const id of [...this.editingIds]) {
            try {
                const response = await axios.put(@js(route('project-budgets.items.update', ['project' => $project, 'item' => 'ITEM_ID'])).replace('ITEM_ID', id), this.drafts[id]);
                lastHtml = response.data.html;
            } catch (error) {
                this.errors[id] = error.response?.data?.errors ?? {};
                failedIds.push(id);
            }
        }
        this.saving = false;
        if (failedIds.length === 0) {
            if (lastHtml) window.refreshSection('project-budget-items-section', lastHtml);
        } else {
            this.editingIds = failedIds;
            alert(@js(__('Some items could not be saved. Please fix the highlighted fields and save again.')));
        }
    },
}">
    <table class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
        <thead class="bg-yellow-100 dark:bg-gray-700">
            <tr>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ __('No.') }}</th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ __('URAIAN') }}</th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ __('QTY.') }}
                </th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ __('UNIT') }}
                </th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('UNIT RATE') }} (Rp)</th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('% of Penawaran') }}</th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ __('TOTAL') }}
                    (Rp)
                </th>
                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
            @forelse ($items as $item)
                @include('project-budgets.partials.item-node', ['item' => $item, 'project' => $project])
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No items yet.') }}</td>
                </tr>
            @endforelse
            @include('project-budgets.partials.item-add-row', [
                'project' => $project,
                'parentId' => null,
                'depth' => 0,
                'rowKey' => 'top-level',
            ])
        </tbody>
        <tfoot>
            <tr class="font-semibold text-gray-900 dark:text-gray-100 border-t-2 border-gray-800 dark:border-gray-200">
                <td class="px-4 py-3" colspan="6">{{ __('TOTAL') }}</td>
                <td class="px-4 py-3">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td class="px-4 py-3"></td>
            </tr>
        </tfoot>
    </table>

    <div x-show="editingIds.length > 0" x-cloak
        class="sticky bottom-0 z-10 mt-4 flex items-center gap-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
        <span class="mr-auto text-sm text-gray-500 dark:text-gray-400"
            x-text="editingIds.length + ' {{ __('item(s) being edited') }}'"></span>
        <button type="button" x-on:click="cancelAll()" :disabled="saving"
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50">
            {{ __('Cancel') }}
        </button>
        <button type="button" x-on:click="saveAll()" :disabled="saving"
            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white disabled:opacity-50">
            <span x-show="!saving">{{ __('Save') }}</span>
            <span x-show="saving">{{ __('Saving...') }}</span>
        </button>
    </div>
</div>
