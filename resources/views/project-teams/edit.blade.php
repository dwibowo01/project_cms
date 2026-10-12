<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Team') }}: {{ $project->project_no }} — {{ $project->project_name }}
            </h2>
            <a href="{{ route('project-teams.index') }}"
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    @php
                        $selectedManagerId = (string) old('manager_id', (string) ($managerId ?? ''));
                        $selectedMemberIds = collect(old('member_ids', $memberIds))->map(fn ($id) => (string) $id);
                    @endphp

                    <form method="POST" action="{{ route('project-teams.update', $project) }}"
                        x-data="{ managerId: '{{ $selectedManagerId }}' }">
                        @csrf
                        @method('put')

                        <!-- Project Manager Docking -->
                        <div class="mb-6">
                            <x-input-label for="manager_id" :value="__('Project Manager Docking')" />
                            <select id="manager_id" name="manager_id" x-model="managerId"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">{{ __('None') }}</option>
                                @foreach ($members as $option)
                                    <option value="{{ $option->id }}">
                                        {{ $option->name }} ({{ $option->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Only one member of this project can be Project Manager Docking.') }}
                            </p>
                        </div>

                        <!-- Member Docking -->
                        <div class="mb-6">
                            <x-input-label :value="__('Member Docking')" />
                            <div
                                class="mt-1 border border-gray-300 dark:border-gray-700 rounded-md divide-y divide-gray-100 dark:divide-gray-700 max-h-72 overflow-y-auto">
                                @foreach ($members as $option)
                                    <label
                                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300"
                                        :class="managerId === '{{ $option->id }}' ? 'opacity-50' : ''">
                                        <input type="checkbox" name="member_ids[]" value="{{ $option->id }}"
                                            {{ $selectedMemberIds->contains((string) $option->id) ? 'checked' : '' }}
                                            :disabled="managerId === '{{ $option->id }}'"
                                            class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                        {{ $option->name }} ({{ $option->email }})
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('member_ids')" class="mt-2" />
                            <x-input-error :messages="$errors->get('member_ids.*')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Any number of members can be Member Docking.') }}
                            </p>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Save Team') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
