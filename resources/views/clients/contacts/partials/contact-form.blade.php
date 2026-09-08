<!-- Name -->
<div class="mb-6">
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $contact->name ?? '')" required
        autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Position -->
<div class="mb-6">
    <x-input-label for="position" :value="__('Position')" />
    <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position', $contact->position ?? '')" />
    <x-input-error :messages="$errors->get('position')" class="mt-2" />
</div>

<!-- Phone Number -->
<div class="mb-6">
    <x-input-label for="phone_number" :value="__('Phone Number')" />
    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $contact->phone_number ?? '')" />
    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
</div>

<!-- Email -->
<div class="mb-6">
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $contact->email ?? '')" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
