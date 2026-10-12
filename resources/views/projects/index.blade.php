<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Project') }}
            </h2>
            <a href="{{ route('projects.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Create Project') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <form method="GET" action="{{ route('projects.index') }}"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 mb-4 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="search" :value="__('Search')" />
                    <x-text-input id="search" name="search" type="text" class="block mt-1 w-full"
                        value="{{ request('search') }}" placeholder="{{ __('Search by project no. or name') }}" />
                </div>
                <div class="min-w-[180px]">
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}"
                                {{ request('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-primary-button type="submit">{{ __('Filter') }}</x-primary-button>
                    <a href="{{ route('projects.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>

            @if ($projects->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table
                            class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Project No.') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Project Name') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Client Name') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Type') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Docking Start') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-gray-700 border-t border-gray-100 dark:border-gray-700">
                                @foreach ($projects as $project)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $project->project_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $project->project_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $project->client->client_name ?: $project->client->client_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $project->project_type->value }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $project->docking_start_date?->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                {{ $project->status->value }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                {{ __('View') }}
                                            </a>
                                            <a href="{{ route('projects.edit', $project) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                {{ __('Edit') }}
                                            </a>
                                            <button type="button" x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-project-deletion-{{ $project->id }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                {{ __('Delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                    @include('projects.partials.delete-project-modal', ['project' => $project])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4">{{ $projects->links() }}</div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No projects yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
