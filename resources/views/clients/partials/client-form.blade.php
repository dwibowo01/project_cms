@php
    $dialCodes = [
        '+62' => 'Indonesia (+62)',
        '+1' => 'United States (+1)',
        '+44' => 'United Kingdom (+44)',
        '+61' => 'Australia (+61)',
        '+65' => 'Singapore (+65)',
        '+60' => 'Malaysia (+60)',
        '+66' => 'Thailand (+66)',
        '+84' => 'Vietnam (+84)',
        '+63' => 'Philippines (+63)',
        '+81' => 'Japan (+81)',
        '+82' => 'South Korea (+82)',
        '+86' => 'China (+86)',
        '+91' => 'India (+91)',
        '+971' => 'United Arab Emirates (+971)',
        '+966' => 'Saudi Arabia (+966)',
        '+49' => 'Germany (+49)',
        '+33' => 'France (+33)',
        '+31' => 'Netherlands (+31)',
        '+39' => 'Italy (+39)',
        '+34' => 'Spain (+34)',
    ];
    $selectedDialCode = old('client_phone_country_code', $client->client_phone_country_code ?? '+62');
@endphp

<!-- Client No / Client ID -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div>
        <x-input-label for="client_no" :value="__('Client No.')" />
        <x-text-input id="client_no" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="text"
            :value="$client->client_no ?? __('Automatically generated')" disabled />
    </div>

    <div>
        <x-input-label for="client_id" :value="__('Client ID')" />
        <x-text-input id="client_id" class="block mt-1 w-full" type="text" name="client_id" :value="old('client_id', $client->client_id ?? '')" />
        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
    </div>
</div>

<!-- Company Type -->
<div class="mb-6">
    <x-input-label for="client_company_type" :value="__('Company Type')" />
    <select id="client_company_type" name="client_company_type" required
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
        @php $selectedCompanyType = old('client_company_type', $client->client_company_type ?? ''); @endphp
        <option value="" disabled {{ $selectedCompanyType === '' ? 'selected' : '' }}>
            {{ __('Select company type') }}
        </option>
        @foreach (\App\Models\Client::COMPANY_TYPES as $type)
            <option value="{{ $type }}" {{ $selectedCompanyType === $type ? 'selected' : '' }}>
                {{ __($type) }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('client_company_type')" class="mt-2" />
</div>

<!-- Client Name -->
<div class="mb-6">
    <x-input-label for="client_name" :value="__('Client Name')" />
    <x-text-input id="client_name" class="block mt-1 w-full" type="text" name="client_name" :value="old('client_name', $client->client_name ?? '')" />
    <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
</div>

<!-- Phone Number -->
<div class="mb-6">
    <x-input-label for="client_phone_number" :value="__('Phone Number')" />
    <div class="mt-1 flex gap-2">
        <select id="client_phone_country_code" name="client_phone_country_code"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-48">
            @foreach ($dialCodes as $code => $label)
                <option value="{{ $code }}" {{ $selectedDialCode === $code ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-text-input id="client_phone_number" class="block w-full" type="text" name="client_phone_number"
            :value="old('client_phone_number', $client->client_phone_number ?? '')" placeholder="812xxxxxxx" />
    </div>
    <x-input-error :messages="$errors->get('client_phone_country_code')" class="mt-2" />
    <x-input-error :messages="$errors->get('client_phone_number')" class="mt-2" />
</div>

<!-- Email -->
<div class="mb-6">
    <x-input-label for="client_email" :value="__('Email')" />
    <x-text-input id="client_email" class="block mt-1 w-full" type="email" name="client_email" :value="old('client_email', $client->client_email ?? '')"
        required />
    <x-input-error :messages="$errors->get('client_email')" class="mt-2" />
</div>

<!-- Others (optional) -->
<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">
        {{ __('Others (optional)') }}
    </h4>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <x-input-label for="company_address_line_1" :value="__('Company Address Line 1')" />
            <x-text-input id="company_address_line_1" class="block mt-1 w-full" type="text"
                name="company_address_line_1" :value="old('company_address_line_1', $client->company_address_line_1 ?? '')" />
            <x-input-error :messages="$errors->get('company_address_line_1')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="company_address_line_2" :value="__('Company Address Line 2')" />
            <x-text-input id="company_address_line_2" class="block mt-1 w-full" type="text"
                name="company_address_line_2" :value="old('company_address_line_2', $client->company_address_line_2 ?? '')" />
            <x-input-error :messages="$errors->get('company_address_line_2')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country', $client->country ?? '')" />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="state" :value="__('State')" />
            <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state', $client->state ?? '')" />
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $client->city ?? '')" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="postal_code" :value="__('Postal Code')" />
            <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code"
                :value="old('postal_code', $client->postal_code ?? '')" />
            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
        </div>
    </div>

    <div class="mb-6">
        <x-input-label for="website" :value="__('Website')" />
        <x-text-input id="website" class="block mt-1 w-full" type="text" name="website" :value="old('website', $client->website ?? '')"
            placeholder="https://example.com" />
        <x-input-error :messages="$errors->get('website')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="notes" :value="__('Notes (bank account, etc.)')" />
        <textarea id="notes" name="notes" rows="4"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('notes', $client->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>
