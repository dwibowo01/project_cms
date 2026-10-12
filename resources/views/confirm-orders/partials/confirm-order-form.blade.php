@php
    $selectedQuotationId = old('quotation_id', $confirmOrder->quotation_id ?? '');
    $selectedPpn = old('ppn', isset($confirmOrder) ? ($confirmOrder->ppn ? '1' : '0') : '1');
@endphp

@if (isset($confirmOrder))
    <!-- Quotation is fixed once the Confirm Order (and its Project) has been created -->
    <div
        class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 text-sm space-y-1">
        <p><span
                class="inline-block w-36 font-medium text-gray-600 dark:text-gray-400">{{ __('Quotation Number') }}</span><span
                class="inline-block w-3">:</span><span>{{ $confirmOrder->quotation->quote_no }}</span></p>
        <p><span
                class="inline-block w-36 font-medium text-gray-600 dark:text-gray-400">{{ __('Project Name') }}</span><span
                class="inline-block w-3">:</span><span>{{ $confirmOrder->project->project_name }}</span></p>
        <p><span
                class="inline-block w-36 font-medium text-gray-600 dark:text-gray-400">{{ __('Jumlah Down Payment') }}</span><span
                class="inline-block w-3">:</span><span>Rp
                {{ number_format($confirmOrder->down_payment_amount, 0, ',', '.') }}</span></p>
    </div>
@else
    <div class="mb-6">
        <x-input-label for="quotation_id" :value="__('Quotation Number')" />
        <select id="quotation_id" name="quotation_id" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="" disabled {{ $selectedQuotationId === '' ? 'selected' : '' }}>
                {{ __('Select quotation') }}
            </option>
            @foreach ($quotations as $option)
                <option value="{{ $option->id }}"
                    {{ (string) $selectedQuotationId === (string) $option->id ? 'selected' : '' }}>
                    {{ $option->quote_no }} — {{ $option->ship->ship_name }}
                    ({{ $option->client->client_name ?: $option->client->client_no }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('quotation_id')" class="mt-2" />
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ __('A Project (with its Repair List, Project Budget, and an empty Project Team ready to be assigned) will be created automatically, along with a Down Payment invoice for the amount below.') }}
        </p>
    </div>

    <div class="mb-6" x-data="{ downPaymentAmount: {{ (float) old('down_payment_amount', 0) }} }">
        <x-input-label for="down_payment_amount" :value="__('Jumlah Down Payment')" />
        <input type="text" inputmode="numeric" placeholder="0" autocomplete="off" id="down_payment_amount"
            x-bind:value="downPaymentAmount ? new Intl.NumberFormat('id-ID').format(downPaymentAmount) : ''"
            x-on:input="downPaymentAmount = $event.target.value.replace(/[^0-9]/g, '')"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
        <input type="hidden" name="down_payment_amount" x-bind:value="downPaymentAmount">
        <x-input-error :messages="$errors->get('down_payment_amount')" class="mt-2" />
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div>
        <x-input-label for="docking_start_date" :value="__('Docking Start Date')" />
        <x-text-input id="docking_start_date" class="block mt-1 w-full" type="date" name="docking_start_date"
            :value="old(
                'docking_start_date',
                optional($confirmOrder->docking_start_date ?? null)->format('Y-m-d') ?? now()->toDateString(),
            )" required />
        <x-input-error :messages="$errors->get('docking_start_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label :value="__('Pengenaan PPN')" />
        <div class="mt-2 flex items-center gap-6">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="radio" name="ppn" value="1" {{ $selectedPpn === '1' ? 'checked' : '' }}
                    class="border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                {{ __('PPN') }}
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="radio" name="ppn" value="0" {{ $selectedPpn === '0' ? 'checked' : '' }}
                    class="border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                {{ __('NON PPN') }}
            </label>
        </div>
        <x-input-error :messages="$errors->get('ppn')" class="mt-2" />
    </div>
</div>
