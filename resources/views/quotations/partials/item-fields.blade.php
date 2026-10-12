@php
    $units = ['Lot', 'Day', 'Pcs', 'M2', 'M3', 'M', 'Kg', 'Set'];
    $indentPx = $indentPx ?? 0;
    $showWhen = $showWhen ?? 'true';
    $draftPath = $draftPath ?? 'draft';
    $errorsPath = $errorsPath ?? 'errors';
@endphp

<td x-show="{{ $showWhen }}" class="px-4 py-3 align-top" style="padding-left: {{ 16 + $indentPx }}px">
    <textarea rows="2" x-model="{{ $draftPath }}.name" required autocomplete="off"
        placeholder="{{ __('Item description') }}"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full text-sm"></textarea>
    <p x-show="{{ $errorsPath }}?.name" x-text="{{ $errorsPath }}?.name?.[0]" class="mt-1 text-xs text-red-600"></p>
</td>
<td x-show="{{ $showWhen }}" class="px-4 py-3 align-top whitespace-nowrap">
    <input type="number" step="1" min="0" x-model="{{ $draftPath }}.qty" autocomplete="off"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-20 text-sm" />
    <p x-show="{{ $errorsPath }}?.qty" x-text="{{ $errorsPath }}?.qty?.[0]" class="mt-1 text-xs text-red-600"></p>
</td>
<td x-show="{{ $showWhen }}" class="px-4 py-3 align-top whitespace-nowrap">
    <input list="unit-options-{{ $rowKey }}" type="text" x-model="{{ $draftPath }}.unit"
        autocomplete="off"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-24 text-sm" />
    <datalist id="unit-options-{{ $rowKey }}">
        @foreach ($units as $unit)
            <option value="{{ $unit }}"></option>
        @endforeach
    </datalist>
    <p x-show="{{ $errorsPath }}?.unit" x-text="{{ $errorsPath }}?.unit?.[0]" class="mt-1 text-xs text-red-600">
    </p>
</td>
<td x-show="{{ $showWhen }}" class="px-4 py-3 align-top whitespace-nowrap">
    <input type="text" inputmode="numeric" placeholder="0" autocomplete="off"
        x-bind:value="{{ $draftPath }}.unit_price ? new Intl.NumberFormat('id-ID').format({{ $draftPath }}.unit_price) : ''"
        x-on:input="{{ $draftPath }}.unit_price = $event.target.value.replace(/[^0-9]/g, '')"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-28 text-sm" />
    <p x-show="{{ $errorsPath }}?.unit_price" x-text="{{ $errorsPath }}?.unit_price?.[0]"
        class="mt-1 text-xs text-red-600"></p>
</td>
