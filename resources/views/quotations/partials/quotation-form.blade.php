@php
    $selectedClientId = old('client_id', $quotation->client_id ?? '');
    $selectedContactId = old('client_contact_id', $quotation->client_contact_id ?? '');
    $selectedShipId = old('ship_id', $quotation->ship_id ?? '');
    $selectedSurveyType = old('survey_type', $quotation->survey_type ?? '');
@endphp

<div x-data="{
    clientId: '{{ $selectedClientId }}',
    contacts: @js($clientContacts->map(fn($c) => ['id' => $c->id, 'client_id' => $c->client_id, 'label' => $c->name])),
    ships: @js($ships->map(fn($s) => ['id' => $s->id, 'client_id' => $s->client_id, 'label' => $s->ship_name])),
    get filteredContacts() { return this.contacts.filter(c => String(c.client_id) === String(this.clientId)); },
    get filteredShips() { return this.ships.filter(s => String(s.client_id) === String(this.clientId)); },
}">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <x-input-label for="client_id" :value="__('Company Name')" />
            <select id="client_id" name="client_id" required x-model="clientId"
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

        <div>
            <x-input-label for="client_contact_id" :value="__('Attn (Contact Person)')" />
            <select id="client_contact_id" name="client_contact_id"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                <option value="">{{ __('None') }}</option>
                <template x-for="contact in filteredContacts" :key="contact.id">
                    <option :value="contact.id" x-text="contact.label"
                        :selected="String(contact.id) === '{{ $selectedContactId }}'"></option>
                </template>
            </select>
            <x-input-error :messages="$errors->get('client_contact_id')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <x-input-label for="ship_id" :value="__('Ship Name')" />
            <select id="ship_id" name="ship_id" required
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                <option value="" disabled {{ $selectedShipId === '' ? 'selected' : '' }}>
                    {{ __('Select ship') }}
                </option>
                <template x-for="ship in filteredShips" :key="ship.id">
                    <option :value="ship.id" x-text="ship.label"
                        :selected="String(ship.id) === '{{ $selectedShipId }}'"></option>
                </template>
            </select>
            <x-input-error :messages="$errors->get('ship_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="survey_type" :value="__('Jenis Survey')" />
            <select id="survey_type" name="survey_type" required
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                <option value="" disabled {{ $selectedSurveyType === '' ? 'selected' : '' }}>
                    {{ __('Select survey type') }}
                </option>
                @foreach (\App\Models\Quotation::SURVEY_TYPES as $type)
                    <option value="{{ $type }}" {{ $selectedSurveyType === $type ? 'selected' : '' }}>
                        {{ $type }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('survey_type')" class="mt-2" />
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-{{ isset($quotation) ? 3 : 2 }} gap-6 mb-6">
    <div>
        <x-input-label for="docking_year" :value="__('Docking Year')" />
        <x-text-input id="docking_year" class="block mt-1 w-full" type="number" min="2000" max="2100"
            name="docking_year" :value="old('docking_year', $quotation->docking_year ?? now()->year)" required />
        <x-input-error :messages="$errors->get('docking_year')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="quotation_date" :value="__('Date')" />
        <x-text-input id="quotation_date" class="block mt-1 w-full" type="date" name="quotation_date"
            :value="old(
                'quotation_date',
                optional($quotation->quotation_date ?? null)->format('Y-m-d') ?? now()->toDateString(),
            )" required />
        <x-input-error :messages="$errors->get('quotation_date')" class="mt-2" />
    </div>

    @isset($quotation)
        <div>
            <x-input-label for="revision" :value="__('Revision')" />
            <x-text-input id="revision" class="block mt-1 w-full" type="number" min="0" name="revision"
                :value="old('revision', $quotation->revision ?? 0)" />
            <x-input-error :messages="$errors->get('revision')" class="mt-2" />
        </div>
    @endisset
</div>
