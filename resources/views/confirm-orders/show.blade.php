<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Confirm Order') }}: {{ $confirmOrder->confirm_order_no }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('confirm-orders.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
                <a href="{{ route('confirm-orders.edit', $confirmOrder) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('projects.show', $confirmOrder->project) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('View Project') }}
                </a>
                <a href="{{ route('confirm-orders.export', $confirmOrder) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Export PDF') }}
                </a>
                <x-danger-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-confirm-order-deletion-{{ $confirmOrder->id }}')">
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
                    </div>

                    <!-- Title -->
                    <h1
                        class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 border-y border-gray-800 dark:border-gray-200 py-3 mb-2">
                        {{ __('ORDER CONFIRMATION') }}
                    </h1>
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ $confirmOrder->confirm_order_no }}
                    </p>

                    <!-- Reference / Client block -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 text-sm">
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Tanggal') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->quotation->quotation_date?->format('d M Y') }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('No. (Quotation Number)') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->quotation->quote_no }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Project') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->project->project_name }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Perusahaan') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->quotation->client->client_name ?: $confirmOrder->quotation->client->client_no }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Alamat') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ trim(($confirmOrder->quotation->client->company_address_line_1 ?? '') . ' ' . ($confirmOrder->quotation->client->company_address_line_2 ?? '')) ?: '-' }}</span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Contact Person') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->quotation->clientContact?->name ?? '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('No. Telp') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ trim(($confirmOrder->quotation->client->client_phone_country_code ?? '') . ' ' . ($confirmOrder->quotation->client->client_phone_number ?? '')) ?: '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Jadwal Mulai Docking') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->docking_start_date?->format('d M Y') }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Pengenaan PPN') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $confirmOrder->ppn ? __('PPN') : __('NON PPN') }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-40 font-semibold text-gray-700 dark:text-gray-300">{{ __('Jumlah Down Payment') }}</span><span
                                    class="inline-block w-3">:</span><span>Rp
                                    {{ number_format($confirmOrder->down_payment_amount, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Scope of Work -->
                    <div>
                        <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Scope of Work') }}</p>
                        <table
                            class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
                                        {{ __('Scope of Work') }}</th>
                                    <th scope="col"
                                        class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 text-right">
                                        {{ __('Fee') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($confirmOrder->scope_of_work as $item)
                                    <tr>
                                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                            {{ $item->name }}</td>
                                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-semibold">
                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">
                                        {{ __('TOTAL') }}</td>
                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-right">
                                        Rp {{ number_format($confirmOrder->scope_of_work_total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('confirm-orders.partials.delete-confirm-order-modal', ['confirmOrder' => $confirmOrder])
</x-app-layout>
