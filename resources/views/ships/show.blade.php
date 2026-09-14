<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ship Details') }}: {{ $ship->ship_name }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('ships.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    {{ __('Back to List') }}
                </a>
                <a href="{{ route('ships.edit', $ship) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                    {{ __('Edit') }}
                </a>
                <x-danger-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-ship-deletion-{{ $ship->id }}')"
                    class="inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    {{ __('Delete') }}
                </x-danger-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="p-3 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 text-cyan-600 dark:text-cyan-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 17l2-2h14l2 2-2 2H5l-2-2zm2-2V8h14v7m-9-7V5h4v3m-9 9v2m14-2v2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Basic Information') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('General details about this ship') }}
                            </p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ship Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ship->ship_name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ship Type') }}</dt>
                            <dd class="mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 dark:bg-cyan-900/30 text-cyan-800 dark:text-cyan-200">
                                    {{ $ship->ship_type }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Company Name') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <a href="{{ route('clients.show', $ship->client) }}"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    {{ $ship->client->client_name ?: $ship->client->client_no }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('LOA') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $ship->loa_value }} {{ $ship->loa_unit }}
                            </dd>
                        </div>

                        @if ($ship->lbp_value)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('LBP') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ship->lbp_value }} {{ $ship->lbp_unit }}
                                </dd>
                            </div>
                        @endif

                        @if ($ship->height_value)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Height') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ship->height_value }} {{ $ship->height_unit }}
                                </dd>
                            </div>
                        @endif

                        @if ($ship->width_value)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Width') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ship->width_value }} {{ $ship->width_unit }}
                                </dd>
                            </div>
                        @endif

                        @if ($ship->draught_value)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Draught') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ship->draught_value }} {{ $ship->draught_unit }}
                                </dd>
                            </div>
                        @endif

                        @if ($ship->gt || $ship->nt)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('GT / NT') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ship->gt ?? '-' }} / {{ $ship->nt ?? '-' }} {{ __('tonnage') }}
                                </dd>
                            </div>
                        @endif

                        @if ($ship->power_me)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Power M/E') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ship->power_me }} {{ __('HP') }}
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $ship->created_at->toDayDateTimeString() }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $ship->updated_at->toDayDateTimeString() }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    @include('ships.partials.delete-ship-modal')
</x-app-layout>
