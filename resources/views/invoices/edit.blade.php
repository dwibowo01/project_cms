<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Invoice') }}: {{ $invoice->invoice_no }}
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
                    @php
                        $latestQuotation = $confirmOrder->quotation->revisions->last() ?? $confirmOrder->quotation;
                        $itemsByRepairListItemId = $invoice->items->keyBy('repair_list_item_id');
                        $requiresScopeOfWork = $invoice->invoice_type->requiresScopeOfWork();
                        $initialItems = $scopeOfWork->mapWithKeys(
                            fn($item) => [
                                $item->id => [
                                    'fee' => (float) $item->quotation_total,
                                    'progress' => (float) old(
                                        "items.{$item->id}.progress",
                                        $itemsByRepairListItemId->get($item->id)?->progress_percentage ?? 0,
                                    ),
                                    'discount' => (float) old(
                                        "items.{$item->id}.discount",
                                        $itemsByRepairListItemId->get($item->id)?->discount_percentage ?? 0,
                                    ),
                                ],
                            ],
                        );
                    @endphp

                    <form method="POST" action="{{ route('invoices.update', $invoice) }}" x-data="{
                        amount: {{ (float) old('amount', $invoice->amount) }},
                        discountPercentage: {{ (float) old('discount_percentage', $invoice->discount_percentage ?? 0) }},
                        ppnPercentage: {{ (float) old('ppn_percentage', $invoice->ppn_percentage ?? 11) }},
                        items: @js($initialItems),
                        get grossAmount() {
                            return Object.values(this.items).reduce((sum, i) => sum + (Number(i.fee) * (Number(i.progress) || 0) / 100 * (1 - (Number(i.discount) || 0) / 100)), 0);
                        },
                        deductionAmount: {{ (float) ($requiresScopeOfWork ? ($invoice->invoice_type->value === 'Progress Payment' ? $paidDownPayment : ($invoice->invoice_type->value === 'Final Payment' ? $paidDownPaymentAndProgress : 0)) : 0) }},
                        get netAmount() {
                            return {{ $requiresScopeOfWork ? 'true' : 'false' }} ? Math.max(this.grossAmount - this.deductionAmount, 0) : (Number(this.amount) || 0);
                        },
                        get discountAmount() { return {{ $requiresScopeOfWork ? 'true' : 'false' }} ? this.netAmount * (Number(this.discountPercentage) || 0) / 100 : 0; },
                        get taxableAmount() { return this.netAmount - this.discountAmount; },
                        get ppnAmount() { return {{ $requiresScopeOfWork ? 'true' : 'false' }} ? this.taxableAmount * (Number(this.ppnPercentage) || 0) / 100 : 0; },
                        get totalAmount() { return this.taxableAmount + this.ppnAmount; },
                        formatRp(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(value || 0)); },
                    }">
                        @csrf
                        @method('put')

                        <!-- Confirm Order / Quotation reference (fixed) -->
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
                            <p><span class="inline-block w-40 font-medium text-gray-600 dark:text-gray-400">{{ __('Jenis Invoice') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->invoice_type->value }}</span></p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="invoice_date" :value="__('Tanggal Invoice')" />
                                <x-text-input id="invoice_date" class="block mt-1 w-full" type="date"
                                    name="invoice_date"
                                    :value="old('invoice_date', $invoice->invoice_date?->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" required
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    @foreach (\App\Enums\InvoiceStatus::cases() as $status)
                                        <option value="{{ $status->value }}"
                                            {{ old('status', $invoice->status->value) === $status->value ? 'selected' : '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        @if (!$requiresScopeOfWork)
                            <!-- Down Payment: manual amount, no scope of work / PPN -->
                            <div class="mb-6">
                                <x-input-label for="amount" :value="__('Jumlah Invoice')" />
                                <input type="text" inputmode="numeric" placeholder="0" autocomplete="off"
                                    x-bind:value="amount ? new Intl.NumberFormat('id-ID').format(amount) : ''"
                                    x-on:input="amount = $event.target.value.replace(/[^0-9]/g, '')"
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
                                <input type="hidden" name="amount" x-bind:value="amount">
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                        @else
                            <!-- Progress Payment / Final Payment / Proforma: scope of work + progress + PPN -->
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
                                @if ($invoice->invoice_type->value === 'Progress Payment')
                                    <p class="flex justify-between text-red-600 dark:text-red-400">
                                        <span>{{ __('Dikurangi DP Terbayar') }}</span><span x-text="'- ' + formatRp(deductionAmount)"></span>
                                    </p>
                                @elseif ($invoice->invoice_type->value === 'Final Payment')
                                    <p class="flex justify-between text-red-600 dark:text-red-400">
                                        <span>{{ __('Dikurangi DP + Progress Terbayar') }}</span><span
                                            x-text="'- ' + formatRp(deductionAmount)"></span>
                                    </p>
                                @endif
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
                        @endif

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Update Invoice') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
