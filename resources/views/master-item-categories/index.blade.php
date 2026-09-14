<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Master Item Categories') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('master-items.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    {{ __('Back to List') }}
                </a>
                <button type="button" x-data=""
                    x-on:click="$dispatch('open-modal', 'create-master-item-category')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Add Category') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    @if ($categories->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table
                                class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Name') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Item') }}</th>
                                        <th scope="col"
                                            class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"></th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
                                    @foreach ($categories as $category)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <th class="px-4 py-3 font-normal text-gray-900 dark:text-gray-100">
                                                {{ $category->name }}</th>
                                            <td class="px-4 py-3">{{ $category->items_count }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <button type="button" x-data=""
                                                    x-on:click="$dispatch('open-modal', 'edit-master-item-category-{{ $category->id }}')"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                    {{ __('Edit') }}
                                                </button>
                                                <button type="button" x-data=""
                                                    x-on:click="$dispatch('open-modal', 'delete-master-item-category-{{ $category->id }}')"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    {{ __('Delete') }}
                                                </button>
                                            </td>
                                        </tr>

                                        <x-modal name="{{ 'edit-master-item-category-' . $category->id }}" focusable>
                                            <form method="post"
                                                action="{{ route('master-item-categories.update', $category) }}"
                                                class="p-6">
                                                @csrf
                                                @method('put')

                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('Edit Category') }}
                                                </h2>

                                                <div class="mt-6">
                                                    <x-input-label for="edit_name_{{ $category->id }}"
                                                        :value="__('Name')" />
                                                    <x-text-input id="edit_name_{{ $category->id }}" name="name"
                                                        type="text" class="block mt-1 w-full"
                                                        value="{{ $category->name }}" required autofocus />
                                                </div>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>

                                                    <x-primary-button class="ms-3">
                                                        {{ __('Update') }}
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <x-modal name="{{ 'delete-master-item-category-' . $category->id }}" focusable>
                                            <form method="post"
                                                action="{{ route('master-item-categories.destroy', $category) }}"
                                                class="p-6">
                                                @csrf
                                                @method('delete')

                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('Are you sure you want to delete this category?') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ __('Once this category is deleted, all of its master items will also be permanently deleted. This action cannot be undone.') }}
                                                </p>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>

                                                    <x-danger-button class="ms-3">
                                                        {{ __('Delete Category') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No categories') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create-master-item-category" focusable>
        <form method="post" action="{{ route('master-item-categories.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add Category') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                    value="{{ old('name') }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
