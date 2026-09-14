@php
    $indentPx = $item->depth * 20;
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
    <th scope="row" class="px-4 py-3 font-normal text-gray-900 dark:text-gray-100 align-top">
        <span style="padding-left: {{ $indentPx }}px" class="inline-block">
            <span class="font-medium">{{ $item->code }}.</span> {{ $item->name }}
        </span>
    </th>
    <td class="px-4 py-3 align-top whitespace-nowrap">{{ $item->qty ?? '-' }}</td>
    <td class="px-4 py-3 align-top whitespace-nowrap">{{ $item->unit ?? '-' }}</td>
    <td class="px-4 py-3 align-top whitespace-nowrap">
        {{ $item->unit_price ? 'Rp. ' . number_format((float) $item->unit_price, 2, '.', ',') : '-' }}</td>
    <td class="px-4 py-3 align-top whitespace-nowrap">
        <a href="{{ route('master-items.create', ['parent_id' => $item->id]) }}"
            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
            {{ __('Add Sub-item') }}
        </a>
        <a href="{{ route('master-items.edit', $item) }}"
            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
            {{ __('Edit') }}
        </a>
        <button type="button" x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-master-item-deletion-{{ $item->id }}')"
            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
            {{ __('Delete') }}
        </button>
    </td>
</tr>

@include('master-items.partials.delete-master-item-modal', ['item' => $item])

@foreach ($item->children as $child)
    @include('master-items.partials.item-node', ['item' => $child])
@endforeach
