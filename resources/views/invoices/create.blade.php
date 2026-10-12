<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Invoice') }}
            </h2>
            <a href="{{ route('invoices.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    @if (!$confirmOrder)
                        {{-- Step 1: choose which Confirm Order this invoice bills against --}}
                        <form method="GET" action="{{ route('invoices.create') }}">
                            <div class="mb-6">
                                <x-input-label for="confirm_order_id" :value="__('Confirm Order')" />
                                <select id="confirm_order_id" name="confirm_order_id" required
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="" disabled selected>{{ __('Select confirm order') }}</option>
                                    @foreach ($confirmOrders as $option)
                                        <option value="{{ $option->id }}">
                                            {{ $option->confirm_order_no }} — {{ $option->quotation->quote_no }} —
                                            {{ $option->project->project_name }}
                                            ({{ $option->quotation->client->client_name ?: $option->quotation->client->client_no }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('confirm_order_id')" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('The client, project, and quotation number are all derived from the selected Confirm Order.') }}
                                </p>
                            </div>
                            <div class="flex items-center justify-end">
                                <x-primary-button type="submit">{{ __('Continue') }}</x-primary-button>
                            </div>
                        </form>
                    @else
                        @php
                            $latestQuotation = $confirmOrder->quotation->revisions->last() ?? $confirmOrder->quotation;
                            $initialItems = $scopeOfWork->mapWithKeys(
                                fn($item) => [
                                    $item->id => [
                                        'fee' => (float) $item->quotation_total,
                                        'progress' => (float) old("items.{$item->id}.progress", 0),
                                        'discount' => (float) old("items.{$item->id}.discount", 0),
                                    ],
                                ],
                            );
                        @endphp

                        <form method="POST" action="{{ route('invoices.store') }}" x-data="{
                            invoiceType: '{{ old('invoice_type', '') }}',
                            amount: {{ (float) old('amount', 0) }},
                            discountPercentage: {{ (float) old('discount_percentage', 0) }},
                            ppnPercentage: {{ (float) old('ppn_percentage', 11) }},
                            items: @js($initialItems),
                            get requiresScopeOfWork() { return this.invoiceType !== '' && this.invoiceType !== 'Down Payment'; },
                            get grossAmount() {
                                return Object.values(this.items).reduce((sum, i) => sum + (Number(i.fee) * (Number(i.progress) || 0) / 100 * (1 - (Number(i.discount) || 0) / 100)), 0);
                            },
                            get deductionAmount() {
                                if (this.invoiceType === 'Progress Payment') return {{ (float) $paidDownPayment }};
                                if (this.invoiceType === 'Final Payment') return {{ (float) $paidDownPaymentAndProgress }};
                                return 0;
                            },
                            get netAmount() {
                                return this.requiresScopeOfWork ? Math.max(this.grossAmount - this.deductionAmount, 0) : (Number(this.amount) || 0);
                            },
                            get discountAmount() { return this.requiresScopeOfWork ? this.netAmount * (Number(this.discountPercentage) || 0) / 100 : 0; },
                            get taxableAmount() { return this.netAmount - this.discountAmount; },
                            get ppnAmount() { return this.requiresScopeOfWork ? this.taxableAmount * (Number(this.ppnPercentage) || 0) / 100 : 0; },
                            get totalAmount() { return this.taxableAmount + this.ppnAmount; },
                            formatRp(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(value || 0)); },
                        }">
                            @csrf
                            <input type="hidden" name="confirm_order_id" value="{{ $confirmOrder->id }}">

                            <!-- Confirm Order reference (fixed) -->
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 text-sm space-y-1">
                                <p><span class="inline-block w-40 font-medium text-gray-600 dark:text-gray-400">{{ __('Confirm Order No.') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $confirmOrder->confirm_order_no }}</span>
                                </p>
                                <p><span class="inline-block w-40 font-medium text-gray-600 dark:text-gray-400">{{ __('Nomor Penawaran') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $latestQuotation->quote_no }}
                                        @if ($latestQuotation->revision > 0)
                                            ({{ __('Rev.') }} {{ $latestQuotation->revision }})
                                        @endif
                                    </span></p>
                                <p><span class="inline-block w-40 font-medium text-gray-600 dark:text-gray-400">{{ __('Project') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $confirmOrder->project->project_no }} —
                                        {{ $confirmOrder->project->project_name }}</span></p>
                                <p><span class="inline-block w-40 font-medium text-gray-600 dark:text-gray-400">{{ __('Client') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $confirmOrder->quotation->client->client_name ?: $confirmOrder->quotation->client->client_no }}</span>
                                </p>
                                <a href="{{ route('invoices.create') }}"
                                    class="inline-block mt-1 text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    {{ __('Change Confirm Order') }}
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <x-input-label for="invoice_date" :value="__('Tanggal Invoice')" />
                                    <x-text-input id="invoice_date" class="block mt-1 w-full" type="date"
                                        name="invoice_date" :value="old('invoice_date', now()->toDateString())" required />
                                    <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="invoice_type" :value="__('Jenis Invoice')" />
                                    <select id="invoice_type" name="invoice_type" required x-model="invoiceType"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                        <option value="" disabled {{ old('invoice_type') ? '' : 'selected' }}>
                                            {{ __('Select invoice type') }}</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->value }}"
                                                {{ old('invoice_type') === $type->value ? 'selected' : '' }}>
                                                {{ $type->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('invoice_type')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Down Payment: manual amount, no scope of work / PPN -->
                            <div x-show="invoiceType === 'Down Payment'" x-cloak class="mb-6">
                                <x-input-label for="amount" :value="__('Jumlah Invoice')" />
                                <input type="text" inputmode="numeric" placeholder="0" autocomplete="off"
                                    x-bind:value="amount ? new Intl.NumberFormat('id-ID').format(amount) : ''"
                                    x-on:input="amount = $event.target.value.replace(/[^0-9]/g, '')"
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
                                <input type="hidden" name="amount" x-bind:value="amount">
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            <!-- Progress Payment / Final Payment / Proforma: scope of work + progress + PPN -->
                            <div x-show="requiresScopeOfWork" x-cloak>
                                <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Scope of Work') }}</p>
                                <div class="overflow-x-auto mb-4">
                                    <table
                                        class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                                    {{ __('Scope of Work') }}</th>
                                                <th class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                    {{ __('Fee') }}</th>
                                                <th class="px-4 py-2 border border-gray-200 dark:border-gray-700 w-32">
                                                    {{ __('Progress (%)') }}</th>
                                                <th class="px-4 py-2 border border-gray-200 dark:border-gray-700 w-28">
                                                    {{ __('Discount (%)') }}</th>
                                                <th class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                    {{ __('Amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($scopeOfWork as $item)
                                                <tr>
                                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                                        {{ $item->name }}
                                                        <input type="hidden" name="items[{{ $item->id }}][repair_list_item_id]"
                                                            value="{{ $item->id }}">
                                                    </td>
                                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                        <span x-text="formatRp({{ (float) $item->quotation_total }})"></span>
                                                    </td>
                                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                                        <input type="number" step="0.01" min="0" max="100"
                                                            name="items[{{ $item->id }}][progress]"
                                                            x-model="items[{{ $item->id }}].progress"
                                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-24 text-sm" />
                                                    </td>
                                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                                        <input type="number" step="0.01" min="0" max="100"
                                                            name="items[{{ $item->id }}][discount]"
                                                            x-model="items[{{ $item->id }}].discount"
                                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-24 text-sm" />
                                                    </td>
                                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                        <span
                                                            x-text="formatRp(items[{{ $item->id }}].fee * (Number(items[{{ $item->id }}].progress) || 0) / 100 * (1 - (Number(items[{{ $item->id }}].discount) || 0) / 100))"></span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <x-input-error :messages="$errors->get('items')" class="mb-4" />

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <x-input-label for="discount_percentage" :value="__('Diskon (%)')" />
                                        <input type="number" step="0.01" min="0" max="100" id="discount_percentage"
                                            name="discount_percentage" x-model="discountPercentage"
                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
                                        <x-input-error :messages="$errors->get('discount_percentage')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="ppn_percentage" :value="__('PPN (%)')" />
                                        <input type="number" step="0.01" min="0" max="100" id="ppn_percentage"
                                            name="ppn_percentage" x-model="ppnPercentage"
                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
                                        <x-input-error :messages="$errors->get('ppn_percentage')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="text-sm space-y-1 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="flex justify-between"><span>{{ __('Jumlah (Progress)') }}</span><span
                                            x-text="formatRp(grossAmount)"></span></p>
                                    <p x-show="invoiceType === 'Progress Payment'" class="flex justify-between text-red-600 dark:text-red-400">
                                        <span>{{ __('Dikurangi DP Terbayar') }}</span><span x-text="'- ' + formatRp(deductionAmount)"></span>
                                    </p>
                                    <p x-show="invoiceType === 'Final Payment'" class="flex justify-between text-red-600 dark:text-red-400">
                                        <span>{{ __('Dikurangi DP + Progress Terbayar') }}</span><span
                                            x-text="'- ' + formatRp(deductionAmount)"></span>
                                    </p>
                                    <p class="flex justify-between font-semibold"><span>{{ __('Jumlah Invoice') }}</span><span
                                            x-text="formatRp(netAmount)"></span></p>
                                    <p class="flex justify-between text-red-600 dark:text-red-400">
                                        <span x-text="'Diskon (' + (discountPercentage || 0) + '%)'"></span><span
                                            x-text="'- ' + formatRp(discountAmount)"></span>
                                    </p>
                                    <p class="flex justify-between"><span>{{ __('Jumlah setelah Diskon') }}</span><span
                                            x-text="formatRp(taxableAmount)"></span></p>
                                    <p class="flex justify-between"><span x-text="'PPN (' + (ppnPercentage || 0) + '%)'"></span><span
                                            x-text="formatRp(ppnAmount)"></span></p>
                                    <p class="flex justify-between font-bold text-base border-t border-gray-300 dark:border-gray-600 pt-1 mt-1">
                                        <span>{{ __('TOTAL') }}</span><span x-text="formatRp(totalAmount)"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Create Invoice') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
