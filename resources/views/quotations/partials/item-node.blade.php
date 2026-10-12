@php
    $indentPx = $item->depth * 20;
    $isParent = $item->children->isNotEmpty();
    $rowKey = 'item-' . $item->id;
    $draftPath = 'drafts[' . $item->id . ']';
    $errorsPath = 'errors[' . $item->id . ']';
    $isEditingExpr = 'isEditing(' . $item->id . ')';
@endphp

<tr x-data="{
    confirmingDelete: false,
    deleting: false,
    deleteItem() {
        if (this.deleting) return;
        this.deleting = true;
        axios.delete(@js(route('quotations.items.destroy', ['quotation' => $quotation, 'item' => $item])))
            .then((response) => window.refreshItemsSection(response.data.html))
            .finally(() => { this.deleting = false; });
    },
}"
    x-on:click="!{{ $isEditingExpr }} && !confirmingDelete && startEdit({{ $item->id }}, { name: @js($item->name), qty: @js($item->qty), unit: @js($item->unit), unit_price: @js($item->unit_price) })"
    :class="{{ $isEditingExpr }} ? 'bg-amber-50 dark:bg-amber-900/20' : ''"
    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer">
    <td
        class="px-4 py-3 align-top whitespace-nowrap text-gray-900 dark:text-gray-100 {{ $isParent ? 'font-bold' : '' }}">
        {{ $item->code }}.</td>

    {{-- view mode --}}
    <th x-show="!{{ $isEditingExpr }}" scope="row"
        class="px-4 py-3 text-gray-900 dark:text-gray-100 align-top {{ $isParent ? 'font-bold' : 'font-normal' }}">
        <span style="padding-left: {{ $indentPx }}px" class="block">{{ $item->name }}</span>
    </th>
    <td x-show="!{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap">
        {{ $item->qty ? number_format($item->qty, 0, ',', '.') : '-' }}</td>
    <td x-show="!{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap">{{ $item->unit ?? '-' }}</td>
    <td x-show="!{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap">
        {{ $item->qty && $item->unit_price ? 'Rp ' . number_format((float) $item->unit_price, 0, ',', '.') : '-' }}
    </td>
    <td x-show="!{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap">
        {{ $isParent || !$item->total ? '-' : 'Rp ' . number_format($item->total, 0, ',', '.') }}</td>
    <td x-show="!{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap">
        <template x-if="!confirmingDelete">
            <span>
                <button type="button" title="{{ __('Add Sub-item') }}"
                    x-on:click.stop="$dispatch('toggle-add-child', {{ $item->id }})"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
                <button type="button" title="{{ __('Delete') }}" x-on:click.stop="confirmingDelete = true"
                    class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </span>
        </template>
        <template x-if="confirmingDelete">
            <span class="whitespace-nowrap" x-on:click.stop>
                <span class="text-xs text-gray-600 dark:text-gray-400 mr-2">{{ __('Delete?') }}</span>
                <button type="button" x-on:click="deleteItem()" :disabled="deleting"
                    class="font-semibold text-red-600 hover:text-red-900 dark:text-red-400 mr-2 disabled:opacity-50">{{ __('Yes') }}</button>
                <button type="button" x-on:click="confirmingDelete = false"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400">{{ __('No') }}</button>
            </span>
        </template>
    </td>

    {{-- edit mode --}}
    @include('quotations.partials.item-fields', [
        'rowKey' => $rowKey,
        'indentPx' => $indentPx,
        'showWhen' => $isEditingExpr,
        'draftPath' => $draftPath,
        'errorsPath' => $errorsPath,
    ])
    <td x-show="{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap">
        <span
            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format((Number({{ $draftPath }}?.qty) || 0) * (Number({{ $draftPath }}?.unit_price) || 0))"></span>
    </td>
    <td x-show="{{ $isEditingExpr }}" class="px-4 py-3 align-top whitespace-nowrap"></td>
</tr>

@include('quotations.partials.item-add-row', [
    'quotation' => $quotation,
    'parentId' => $item->id,
    'depth' => $item->depth + 1,
    'rowKey' => $rowKey . '-child',
    'toggleId' => $item->id,
])

@foreach ($item->children as $child)
    @include('quotations.partials.item-node', ['item' => $child, 'quotation' => $quotation])
@endforeach
