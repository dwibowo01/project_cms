@php
    $indentPx = ($depth ?? 0) * 20;
    $toggleable = isset($toggleId);
@endphp

<tr x-data="{
    show: {{ $toggleable ? 'false' : 'true' }},
    saving: false,
    errors: {},
    draft: { name: '', qty: '', unit: '', unit_price: '' },
    save() {
        if (this.saving) return;
        this.saving = true;
        axios.post(@js(route('quotations.items.store', $quotation)), { ...this.draft, parent_id: @js($parentId) })
            .then((response) => window.refreshItemsSection(response.data.html))
            .catch((error) => { this.errors = error.response?.data?.errors ?? {}; })
            .finally(() => { this.saving = false; });
    },
}" @if ($toggleable)
    x-on:toggle-add-child.window="if ($event.detail === {{ $toggleId }}) { show = !show; if (show) { draft = { name: '', qty: '', unit: '', unit_price: '' }; errors = {}; } }"
    @endif
    x-show="show"
    class="bg-gray-50/50 dark:bg-gray-900/30"
    >
    <td class="px-4 py-3 align-top whitespace-nowrap text-gray-400 dark:text-gray-500">+</td>
    @include('quotations.partials.item-fields', [
        'rowKey' => $rowKey,
        'indentPx' => $indentPx,
        'draftPath' => 'draft',
        'errorsPath' => 'errors',
    ])
    <td class="px-4 py-3 align-top whitespace-nowrap">
        <span
            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format((Number(draft.qty) || 0) * (Number(draft.unit_price) || 0))"></span>
    </td>
    <td class="px-4 py-3 align-top whitespace-nowrap">
        <button type="button" title="{{ __('Save') }}" x-on:click.stop="save()" :disabled="saving"
            class="inline-flex items-center text-green-600 hover:text-green-900 dark:text-green-400 mr-3 disabled:opacity-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </button>
        @if ($toggleable)
            <button type="button" title="{{ __('Cancel') }}"
                x-on:click.stop="show = false; errors = {}; draft = { name: '', qty: '', unit: '', unit_price: '' }"
                class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </td>
</tr>
