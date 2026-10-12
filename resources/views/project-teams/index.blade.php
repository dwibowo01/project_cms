<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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
                                        {{ __('Project Manager Docking') }}</th>
                                    <th scope="col" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Member Docking') }}</th>
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
                                            {{ $project->project_no }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $project->project_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($project->manager)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                                                    {{ $project->manager->user->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($project->members->isNotEmpty())
                                                {{ $project->members->map(fn ($member) => $member->user->name)->implode(', ') }}
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('project-teams.edit', $project) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ __('Manage Team') }}
                                            </a>
                                        </td>
                                    </tr>
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
