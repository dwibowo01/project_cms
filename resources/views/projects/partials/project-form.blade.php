@php
    $selectedClientId = old('client_id', $project->client_id ?? '');
    $selectedStatus = old('status', $project->status?->value ?? \App\Enums\ProjectStatus::ACTIVE->value);
    $selectedType = old('project_type', $project->project_type?->value ?? \App\Enums\ProjectType::DOCKING->value);
@endphp

<!-- Project Name -->
<div class="mb-6">
    <x-input-label for="project_name" :value="__('Project Name')" />
    <x-text-input id="project_name" class="block mt-1 w-full" type="text" name="project_name"
        :value="old('project_name', $project->project_name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('project_name')" class="mt-2" />
</div>

<!-- Client / Project Type -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div>
        <x-input-label for="client_id" :value="__('Client Name')" />
        <select id="client_id" name="client_id" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="" disabled {{ $selectedClientId === '' ? 'selected' : '' }}>
                {{ __('Select client') }}
            </option>
            @foreach ($clients as $option)
                <option value="{{ $option->id }}"
                    {{ (string) $selectedClientId === (string) $option->id ? 'selected' : '' }}>
                    {{ $option->client_name ?: $option->client_no }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="project_type" :value="__('Project Type')" />
        <select id="project_type" name="project_type" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            @foreach ($types as $type)
                <option value="{{ $type->value }}" {{ $selectedType === $type->value ? 'selected' : '' }}>
                    {{ $type->value }} ({{ $type->code() }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('project_type')" class="mt-2" />
    </div>
</div>

<!-- Docking Start / End Date / Status -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
    <div>
        <x-input-label for="docking_start_date" :value="__('Docking Start Date')" />
        <x-text-input id="docking_start_date" class="block mt-1 w-full" type="date" name="docking_start_date"
            :value="old(
                'docking_start_date',
                optional($project->docking_start_date ?? null)->format('Y-m-d'),
            )" required />
        <x-input-error :messages="$errors->get('docking_start_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="docking_end_date" :value="__('Docking End Date')" />
        <x-text-input id="docking_end_date" class="block mt-1 w-full" type="date" name="docking_end_date"
            :value="old(
                'docking_end_date',
                optional($project->docking_end_date ?? null)->format('Y-m-d'),
            )" />
        <x-input-error :messages="$errors->get('docking_end_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" {{ $selectedStatus === $status->value ? 'selected' : '' }}>
                    {{ $status->value }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>

<!-- Description -->
<div class="mb-6">
    <x-input-label for="description" :value="__('Description (optional)')" />
    <textarea id="description" name="description" rows="3"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $project->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>
