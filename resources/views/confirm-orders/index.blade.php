<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Confirm Order') }}
            </h2>
            <a href="{{ route('confirm-orders.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Create Confirm Order') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <form method="GET" action="{{ route('confirm-orders.index') }}"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 mb-4 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="search" :value="__('Search')" />
                    <x-text-input id="search" name="search" type="text" class="block mt-1 w-full"
                        value="{{ request('search') }}" placeholder="{{ __('Search by confirm order no.') }}" />
                </div>
                <div class="flex gap-2">
                    <x-primary-button type="submit">{{ __('Filter') }}</x-primary-button>
                    <a href="{{ route('confirm-orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>

            @if ($confirmOrders->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table
                            class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Confirm Order No.') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Quotation No.') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Project Name') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Company Name') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Docking Start') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
                                @foreach ($confirmOrders as $confirmOrder)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                            <a href="{{ route('confirm-orders.show', $confirmOrder) }}"
                                                class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $confirmOrder->confirm_order_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $confirmOrder->quotation->quote_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $confirmOrder->project->project_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $confirmOrder->quotation->client->client_name ?: $confirmOrder->quotation->client->client_no }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $confirmOrder->docking_start_date?->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('confirm-orders.show', $confirmOrder) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                {{ __('View') }}
                                            </a>
                                            <a href="{{ route('confirm-orders.edit', $confirmOrder) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                {{ __('Edit') }}
                                            </a>
                                            <a href="{{ route('confirm-orders.export', $confirmOrder) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                {{ __('Export PDF') }}
                                            </a>
                                            <button type="button" x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-confirm-order-deletion-{{ $confirmOrder->id }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                {{ __('Delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                    @include('confirm-orders.partials.delete-confirm-order-modal', [
                                        'confirmOrder' => $confirmOrder,
                                    ])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4">{{ $confirmOrders->links() }}</div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No confirm orders yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
