<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Master Item') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('master-item-categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Manage Categories') }}
                </a>
                <a href="{{ route('master-items.create', ['category_id' => $categoryId]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Add Master Item') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Category Tabs -->
            <div class="mb-4 flex flex-wrap gap-2">
                @forelse ($categories as $category)
                    <a href="{{ route('master-items.index', ['category_id' => $category->id]) }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium
                            {{ $categoryId === $category->id
                                ? 'bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800'
                                : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        {{ $category->name }}
                    </a>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No categories yet.') }}
                        <a href="{{ route('master-item-categories.index') }}" class="text-indigo-600 hover:underline">
                            {{ __('Add Category') }}
                        </a>
                    </p>
                @endforelse
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    @if ($items->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table
                                class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Item') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Qty') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Unit') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Unit Price') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"></th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
                                    @foreach ($items as $item)
                                        @include('master-items.partials.item-node', ['item' => $item])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No items') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
