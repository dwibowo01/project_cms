<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoice') }}: {{ $invoice->invoice_no }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('invoices.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
                <a href="{{ route('invoices.edit', $invoice) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('projects.show', $invoice->project) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('View Project') }}
                </a>
                <x-danger-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-invoice-deletion-{{ $invoice->id }}')">
                    {{ __('Delete') }}
                </x-danger-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    <!-- Letterhead -->
                    <div
                        class="flex items-start justify-between border-b-2 border-gray-800 dark:border-gray-200 pb-4 mb-6">
                        <img src="{{ asset('logo_caputra.png') }}" alt="PT. Caputra Mitra Sejati" class="h-12">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $invoice->status === \App\Enums\InvoiceStatus::PAID
                                    ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200'
                                    : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200' }}">
                            {{ $invoice->status->value }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1
                        class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 border-y border-gray-800 dark:border-gray-200 py-3 mb-2">
                        {{ __('INVOICE') }} — {{ strtoupper($invoice->invoice_type->value) }}
                    </h1>
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ $invoice->invoice_no }}
                    </p>

                    <!-- Client / Reference block -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 text-sm">
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Kepada') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->client?->client_name ?: $invoice->client?->client_no }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Attn') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->confirmOrder->quotation->clientContact?->name ?? '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Email') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->confirmOrder->quotation->clientContact?->email ?? $invoice->client?->client_email }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Alamat') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ trim(($invoice->client?->company_address_line_1 ?? '') . ' ' . ($invoice->client?->company_address_line_2 ?? '')) ?: '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('No. Telp') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ trim(($invoice->client?->client_phone_country_code ?? '') . ' ' . ($invoice->client?->client_phone_number ?? '')) ?: '-' }}</span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Tanggal Invoice') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->invoice_date?->format('d M Y') }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Project') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->project?->project_no }} —
                                    {{ $invoice->project?->project_name }}</span></p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Nomor Penawaran') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->latest_quotation?->quote_no }}
                                    @if ($invoice->latest_quotation && $invoice->latest_quotation->revision > 0)
                                        ({{ __('Rev.') }} {{ $invoice->latest_quotation->revision }})
                                    @endif
                                </span></p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Nomor Confirm Order') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->confirmOrder->confirm_order_no }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Jenis Invoice') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $invoice->invoice_type->value }}</span>
                            </p>
                        </div>
                    </div>

                    @if ($invoice->invoice_type->requiresScopeOfWork())
                        <!-- Scope of Work -->
                        <div class="mb-6">
                            <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Scope of Work') }}</p>
                            <table
                                class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
                                            {{ __('Scope of Work') }}</th>
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 text-right">
                                            {{ __('Fee') }}</th>
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 text-right">
                                            {{ __('Progress') }}</th>
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 text-right">
                                            {{ __('Discount') }}</th>
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 text-right">
                                            {{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                                {{ $item->name }}</td>
                                            <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                Rp {{ number_format($item->fee, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                {{ number_format($item->progress_percentage, 2) }}%</td>
                                            <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                {{ number_format($item->discount_percentage, 2) }}%</td>
                                            <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                                Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="mb-6 text-sm max-w-md ml-auto space-y-1">
                            <p class="flex justify-between"><span>{{ __('Jumlah (Progress)') }}</span>
                                <span>Rp {{ number_format($invoice->gross_amount, 0, ',', '.') }}</span>
                            </p>
                            @if ($invoice->invoice_type->value === 'Progress Payment')
                                <p class="flex justify-between text-red-600 dark:text-red-400">
                                    <span>{{ __('Dikurangi DP Terbayar') }}</span>
                                    <span>- Rp {{ number_format($invoice->deduction_amount, 0, ',', '.') }}</span>
                                </p>
                            @elseif ($invoice->invoice_type->value === 'Final Payment')
                                <p class="flex justify-between text-red-600 dark:text-red-400">
                                    <span>{{ __('Dikurangi DP + Progress Terbayar') }}</span>
                                    <span>- Rp {{ number_format($invoice->deduction_amount, 0, ',', '.') }}</span>
                                </p>
                            @endif
                            <p class="flex justify-between font-semibold"><span>{{ __('Jumlah Invoice') }}</span>
                                <span>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="flex justify-between text-red-600 dark:text-red-400">
                                <span>{{ __('Diskon') }} ({{ number_format($invoice->discount_percentage, 2) }}%)</span>
                                <span>- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="flex justify-between"><span>{{ __('Jumlah setelah Diskon') }}</span>
                                <span>Rp {{ number_format($invoice->taxable_amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="flex justify-between">
                                <span>{{ __('PPN') }} ({{ number_format($invoice->ppn_percentage, 2) }}%)</span>
                                <span>Rp {{ number_format($invoice->ppn_amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="flex justify-between font-bold text-base border-t-2 border-gray-800 dark:border-gray-200 pt-2 mt-1">
                                <span>{{ __('TOTAL') }}</span>
                                <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    @else
                        <!-- Down Payment: no scope of work / PPN -->
                        <div class="mb-6 text-sm max-w-md ml-auto space-y-1">
                            <p class="flex justify-between font-bold text-base border-t-2 border-gray-800 dark:border-gray-200 pt-2 mt-1">
                                <span>{{ __('TOTAL') }}</span>
                                <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    @endif

                    <!-- Terbilang -->
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Terbilang') }} :
                        <span class="font-bold italic">{{ $invoice->terbilang }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('invoices.partials.delete-invoice-modal', ['invoice' => $invoice])
</x-app-layout>
