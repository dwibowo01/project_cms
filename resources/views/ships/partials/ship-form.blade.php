@php
    $units = \App\Models\Ship::UNITS;
    $selectedShipType = old('ship_type', $ship->ship_type ?? '');
    $selectedClientId = old('client_id', $ship->client_id ?? '');
@endphp

<!-- Ship Name -->
<div class="mb-6">
    <x-input-label for="ship_name" :value="__('Ship Name')" />
    <x-text-input id="ship_name" class="block mt-1 w-full" type="text" name="ship_name" :value="old('ship_name', $ship->ship_name ?? '')" required
        autofocus />
    <x-input-error :messages="$errors->get('ship_name')" class="mt-2" />
</div>

<!-- Ship Type / Company Name -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div>
        <x-input-label for="ship_type" :value="__('Ship Type')" />
        <select id="ship_type" name="ship_type" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="" disabled {{ $selectedShipType === '' ? 'selected' : '' }}>
                {{ __('Select ship type') }}
            </option>
            @foreach (\App\Models\Ship::SHIP_TYPES as $type)
                <option value="{{ $type }}" {{ $selectedShipType === $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ship_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="client_id" :value="__('Company Name')" />
        <select id="client_id" name="client_id" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="" disabled {{ $selectedClientId === '' ? 'selected' : '' }}>
                {{ __('Select company') }}
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
</div>

<!-- LOA (required) -->
<div class="mb-6">
    <x-input-label for="loa_value" :value="__('LOA')" />
    <div class="mt-1 flex gap-2">
        <x-text-input id="loa_value" class="block w-full" type="number" step="0.01" min="0" name="loa_value"
            :value="old('loa_value', $ship->loa_value ?? '')" required />
        <select name="loa_unit" required
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-32">
            @php $selectedLoaUnit = old('loa_unit', $ship->loa_unit ?? 'meter'); @endphp
            @foreach ($units as $unit)
                <option value="{{ $unit }}" {{ $selectedLoaUnit === $unit ? 'selected' : '' }}>
                    {{ $unit }}</option>
            @endforeach
        </select>
    </div>
    <x-input-error :messages="$errors->get('loa_value')" class="mt-2" />
    <x-input-error :messages="$errors->get('loa_unit')" class="mt-2" />
</div>

<!-- Others (optional) -->
<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">
        {{ __('Others (optional)') }}
    </h4>

    @foreach (['lbp' => __('LBP'), 'height' => __('Height'), 'width' => __('Width'), 'draught' => __('Draught')] as $field => $label)
        <div class="mb-6">
            <x-input-label for="{{ $field }}_value" :value="$label" />
            <div class="mt-1 flex gap-2">
                <x-text-input id="{{ $field }}_value" class="block w-full" type="number" step="0.01"
                    min="0" name="{{ $field }}_value" :value="old($field . '_value', $ship->{$field . '_value'} ?? '')" />
                <select name="{{ $field }}_unit"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-32">
                    @php $selectedUnit = old($field . '_unit', $ship->{$field . '_unit'} ?? 'meter'); @endphp
                    @foreach ($units as $unit)
                        <option value="{{ $unit }}" {{ $selectedUnit === $unit ? 'selected' : '' }}>
                            {{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <x-input-error :messages="$errors->get($field . '_value')" class="mt-2" />
            <x-input-error :messages="$errors->get($field . '_unit')" class="mt-2" />
        </div>
    @endforeach

    <!-- GT / NT -->
    <div class="mb-6">
        <x-input-label for="gt" :value="__('GT / NT')" />
        <div class="mt-1 flex items-center gap-2">
            <x-text-input id="gt" class="block w-full" type="number" step="0.01" min="0"
                name="gt" :value="old('gt', $ship->gt ?? '')" placeholder="GT" />
            <span class="text-gray-500 dark:text-gray-400">/</span>
            <x-text-input id="nt" class="block w-full" type="number" step="0.01" min="0"
                name="nt" :value="old('nt', $ship->nt ?? '')" placeholder="NT" />
            <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ __('tonnage') }}</span>
        </div>
        <x-input-error :messages="$errors->get('gt')" class="mt-2" />
        <x-input-error :messages="$errors->get('nt')" class="mt-2" />
    </div>

    <!-- Power M/E -->
    <div>
        <x-input-label for="power_me" :value="__('Power M/E')" />
        <div class="mt-1 flex items-center gap-2">
            <x-text-input id="power_me" class="block w-full" type="number" step="0.01" min="0"
                name="power_me" :value="old('power_me', $ship->power_me ?? '')" />
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('HP') }}</span>
        </div>
        <x-input-error :messages="$errors->get('power_me')" class="mt-2" />
    </div>
</div>
