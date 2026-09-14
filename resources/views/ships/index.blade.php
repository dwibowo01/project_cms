<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ship Database') }}
            </h2>
            <a href="{{ route('ships.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Create Ship') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                if (!function_exists('shipSortUrl')) {
                    function shipSortUrl($field, $sort, $direction)
                    {
                        $newDirection = $sort === $field && $direction === 'asc' ? 'desc' : 'asc';

                        return request()->fullUrlWithQuery([
                            'sort' => $field,
                            'direction' => $newDirection,
                            'page' => null,
                        ]);
                    }
                }
            @endphp

            <!-- Filters -->
            <form method="GET" action="{{ route('ships.index') }}"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 mb-4 flex flex-wrap items-end gap-4">
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="direction" value="{{ $direction }}">

                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="search" :value="__('Search')" />
                    <x-text-input id="search" name="search" type="text" class="block mt-1 w-full"
                        value="{{ request('search') }}" placeholder="{{ __('Search by ship name') }}" />
                </div>

                <div class="min-w-[180px]">
                    <x-input-label for="ship_type" :value="__('Ship Type')" />
                    <select id="ship_type" name="ship_type"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                        <option value="">{{ __('All Ship Types') }}</option>
                        @foreach ($shipTypes as $type)
                            <option value="{{ $type }}" {{ request('ship_type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[180px]">
                    <x-input-label for="client_id" :value="__('Company Name')" />
                    <select id="client_id" name="client_id"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                        <option value="">{{ __('All Companies') }}</option>
                        @foreach ($clients as $option)
                            <option value="{{ $option->id }}"
                                {{ (string) request('client_id') === (string) $option->id ? 'selected' : '' }}>
                                {{ $option->client_name ?: $option->client_no }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <x-primary-button type="submit">{{ __('Filter') }}</x-primary-button>
                    <a href="{{ route('ships.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>

            @if ($ships->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table
                            class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @php
                                        $columns = [
                                            'ship_name' => __('Ship Name'),
                                            'ship_type' => __('Type'),
                                            'loa_value' => __('LOA'),
                                        ];
                                    @endphp
                                    @foreach ($columns as $field => $label)
                                        <th scope="col"
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ shipSortUrl($field, $sort, $direction) }}"
                                                class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $label }}
                                                @if ($sort === $field)
                                                    <span>{{ $direction === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Company') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('GT / NT') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ shipSortUrl('created_at', $sort, $direction) }}"
                                            class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400">
                                            {{ __('Created') }}
                                            @if ($sort === 'created_at')
                                                <span>{{ $direction === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
                                @foreach ($ships as $ship)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <th class="px-6 py-4 font-normal text-gray-900 dark:text-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-5 h-5 text-cyan-600 dark:text-cyan-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 17l2-2h14l2 2-2 2H5l-2-2zm2-2V8h14v7m-9-7V5h4v3m-9 9v2m14-2v2" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="font-medium">{{ $ship->ship_name }}</div>
                                            </div>
                                        </th>
                                        <td class="px-6 py-4">{{ $ship->ship_type }}</td>
                                        <td class="px-6 py-4">{{ $ship->loa_value }} {{ $ship->loa_unit }}</td>
                                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                            {{ $ship->client->client_name ?: $ship->client->client_no }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($ship->gt || $ship->nt)
                                                {{ $ship->gt ?? '-' }} / {{ $ship->nt ?? '-' }}
                                            @else
                                                <span
                                                    class="text-gray-400 dark:text-gray-500 italic text-xs">{{ __('Not set') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                            {{ $ship->created_at->toDayDateTimeString() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('ships.show', $ship) }}"
                                                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                                                    title="{{ __('View') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-6 w-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('ships.edit', $ship) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                    title="{{ __('Edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-6 w-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                    </svg>
                                                </a>
                                                <a href="#" x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', {{ '\'confirm-ship-deletion-' . $ship->id . '\'' }})"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="{{ __('Delete') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-6 w-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </a>

                                                @include('ships.partials.delete-ship-modal')
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $ships->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 17l2-2h14l2 2-2 2H5l-2-2zm2-2V8h14v7m-9-7V5h4v3m-9 9v2m14-2v2" />
                        </svg>
                        @if (request()->hasAny(['search', 'ship_type']))
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ __('No matching ships') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Try adjusting your search or filters.') }}</p>
                        @else
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ __('No ships') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Get started by creating a new ship.') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('ships.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Create your first ship') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
