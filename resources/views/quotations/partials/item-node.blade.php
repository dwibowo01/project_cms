@php
    $indentPx = $item->depth * 20;
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
    <td class="px-4 py-3 align-top whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $item->code }}.</td>
    <th scope="row" class="px-4 py-3 font-normal text-gray-900 dark:text-gray-100 align-top">
        <span style="padding-left: {{ $indentPx }}px" class="block">{{ $item->name }}</span>
    </th>
    <td class="px-4 py-3 align-top whitespace-nowrap">{{ $item->qty ? number_format($item->qty, 0, ',', '.') : '-' }}
    </td>
    <td class="px-4 py-3 align-top whitespace-nowrap">{{ $item->unit ?? '-' }}</td>
    <td class="px-4 py-3 align-top whitespace-nowrap">
        {{ $item->qty && $item->unit_price ? 'Rp ' . number_format((float) $item->unit_price, 0, ',', '.') : '-' }}</td>
    <td class="px-4 py-3 align-top whitespace-nowrap">
        {{ $item->total ? 'Rp ' . number_format($item->total, 0, ',', '.') : '-' }}</td>
    <td class="px-4 py-3 align-top whitespace-nowrap">
        <a href="{{ route('quotations.items.create', ['quotation' => $quotation, 'parent_id' => $item->id]) }}"
            title="{{ __('Add Sub-item') }}"
            class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </a>
        <a href="{{ route('quotations.items.edit', ['quotation' => $quotation, 'item' => $item]) }}"
            title="{{ __('Edit') }}"
            class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
            </svg>
        </a>
        <button type="button" x-data="" title="{{ __('Delete') }}"
            x-on:click.prevent="$dispatch('open-modal', 'confirm-quotation-item-deletion-{{ $item->id }}')"
            class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </button>
    </td>
</tr>

@include('quotations.items.partials.delete-quotation-item-modal', [
    'quotation' => $quotation,
    'item' => $item,
])

@foreach ($item->children as $child)
    @include('quotations.partials.item-node', ['item' => $child, 'quotation' => $quotation])
@endforeach
