<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quotation') }}: {{ $quotation->quote_no }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('quotations.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
                <a href="{{ route('quotations.edit', $quotation) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('quotations.export', $quotation) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Export Excel') }}
                </a>
                <form method="POST" action="{{ route('quotations.revise', $quotation) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Create New Revision') }}
                    </button>
                </form>
                {{-- <a href="{{ route('quotations.items.create', $quotation) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Add Master Item') }}
                </a> --}}
                <x-danger-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-quotation-deletion-{{ $quotation->id }}')">
                    {{ __('Delete') }}
                </x-danger-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    <!-- Letterhead -->
                    <div
                        class="flex items-start justify-between border-b-2 border-gray-800 dark:border-gray-200 pb-4 mb-6">
                        <img src="{{ asset('logo_caputra.png') }}" alt="PT. Caputra Mitra Sejati" class="h-12">
                    </div>

                    <!-- To / Date block -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 text-sm">
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('To') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->client->client_name ?: $quotation->client->client_no }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Attn') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->clientContact?->name ?? '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Email') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->clientContact?->email ?? $quotation->client->client_email }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Address') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ trim(($quotation->client->company_address_line_1 ?? '') . ' ' . ($quotation->client->company_address_line_2 ?? '')) ?: '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Phone / Fax') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->client->client_phone_number ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Date') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->quotation_date?->format('d M Y') }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Contact') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->creator?->name ?? '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Email') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->creator?->email ?? '-' }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Quote no.') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->quote_no }}</span></p>
                            <p><span
                                    class="inline-block w-28 font-semibold text-gray-700 dark:text-gray-300">{{ __('Revision') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $quotation->revision }}</span></p>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1
                        class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 border-y border-gray-800 dark:border-gray-200 py-3 mb-6">
                        {{ __('PENAWARAN DOCKING') }} {{ $quotation->ship->ship_name }}
                    </h1>

                    <!-- Ship Identity -->
                    <div class="mb-6">
                        <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('INDENTITAS KAPAL') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                            <div class="space-y-1">
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('NAMA KAPAL') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->ship_name }}</span>
                                </p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('LOA') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->loa_value }}
                                        {{ $quotation->ship->loa_unit }}</span></p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('LBP') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->lbp_value ? $quotation->ship->lbp_value . ' ' . $quotation->ship->lbp_unit : '-' }}</span>
                                </p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('TINGGI') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->height_value ? $quotation->ship->height_value . ' ' . $quotation->ship->height_unit : '-' }}</span>
                                </p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('DOCKING') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ __('TAHUN') }}
                                        {{ $quotation->docking_year }}</span></p>
                            </div>
                            <div class="space-y-1">
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('LEBAR') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->width_value ? $quotation->ship->width_value . ' ' . $quotation->ship->width_unit : '-' }}</span>
                                </p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('DRAFT') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->draught_value ? $quotation->ship->draught_value . ' ' . $quotation->ship->draught_unit : '-' }}</span>
                                </p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('GT / NT') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->gt ?? '-' }} /
                                        {{ $quotation->ship->nt ?? '-' }}</span></p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('DAYA M/E') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->ship->power_me ?? '-' }}</span>
                                </p>
                                <p><span
                                        class="inline-block w-28 font-medium text-gray-600 dark:text-gray-400">{{ __('JENIS SURVEY') }}</span><span
                                        class="inline-block w-3">:</span><span>{{ $quotation->survey_type }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    @if ($items->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table
                                class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-yellow-100 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('No.') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('URAIAN') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('QTY.') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('UNIT') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('UNIT RATE') }} (Rp)</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('TOTAL') }} (Rp)</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"></th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
                                    @foreach ($items as $item)
                                        @include('quotations.partials.item-node', [
                                            'item' => $item,
                                            'quotation' => $quotation,
                                        ])
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr
                                        class="font-semibold text-gray-900 dark:text-gray-100 border-t-2 border-gray-800 dark:border-gray-200">
                                        <td class="px-4 py-3" colspan="5">{{ __('TOTAL') }}</td>
                                        <td class="px-4 py-3">Rp
                                            {{ number_format($quotation->grand_total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('No items yet.') }}
                            <a href="{{ route('quotations.items.create', $quotation) }}"
                                class="text-indigo-600 hover:underline">
                                {{ __('Add Master Item') }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            @if ($quotation->revisions->count() > 1)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Revision History') }}
                        </h3>
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            @foreach ($quotation->revisions->sortByDesc('revision') as $revisionQuotation)
                                <li class="flex items-center justify-between py-2">
                                    <span>
                                        {{ __('Revision') }} {{ $revisionQuotation->revision }}
                                        <span class="text-gray-400">&middot;</span>
                                        {{ optional($revisionQuotation->quotation_date)->format('d M Y') }}
                                        @if ($revisionQuotation->id === $quotation->id)
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">{{ __('Viewing') }}</span>
                                        @endif
                                    </span>
                                    <a href="{{ route('quotations.show', $revisionQuotation) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ __('View') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('quotations.partials.delete-quotation-modal', ['quotation' => $quotation])
</x-app-layout>
