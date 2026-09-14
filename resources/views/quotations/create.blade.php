<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Quotation') }}
            </h2>
            <a href="{{ route('quotations.index') }}"
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
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <!-- Mode Tabs -->
            <div class="mb-4 flex flex-wrap gap-2">
                @php
                    $modes = [
                        'scratch' => __('From Scratch'),
                        'master-item' => __('From Master Item'),
                        'duplicate' => __('From Existing Quotation'),
                    ];
                @endphp
                @foreach ($modes as $modeKey => $label)
                    <a href="{{ route('quotations.create', ['mode' => $modeKey]) }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium
                            {{ $mode === $modeKey
                                ? 'bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800'
                                : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('quotations.store') }}">
                        @csrf
                        <input type="hidden" name="mode" value="{{ $mode }}">

                        @include('quotations.partials.quotation-form')

                        @if ($mode === 'master-item')
                            <div class="mb-6">
                                <x-input-label for="master_item_category_id" :value="__('Master Item Category')" />
                                <select id="master_item_category_id" name="master_item_category_id" required
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="" disabled selected>{{ __('Select category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('master_item_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('master_item_category_id')" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('All items from this category will be copied into the new quotation.') }}
                                </p>
                            </div>
                        @elseif ($mode === 'duplicate')
                            <div class="mb-6">
                                <x-input-label for="source_quotation_id" :value="__('Existing Quotation')" />
                                <select id="source_quotation_id" name="source_quotation_id" required
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="" disabled selected>{{ __('Select quotation') }}</option>
                                    @foreach ($sourceQuotations as $option)
                                        <option value="{{ $option->id }}"
                                            {{ old('source_quotation_id') == $option->id ? 'selected' : '' }}>
                                            {{ $option->quote_no }} — {{ $option->ship->ship_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('source_quotation_id')" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('All items from the selected quotation will be copied into the new quotation.') }}
                                </p>
                            </div>
                        @else
                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('The quotation will start empty — add items one by one after it is created.') }}
                            </p>
                        @endif

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
