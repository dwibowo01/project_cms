<x-modal name="{{ 'confirm-client-deletion-' . $client->id }}" :show="$errors->{$client->id}->isNotEmpty()" focusable>
    <form method="post" action="{{ route('clients.destroy', $client) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete this client?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once this client is deleted, all of their data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this client.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="password-{{ $client->id }}" value="{{ __('Password') }}" class="sr-only" />
            <x-text-input id="password-{{ $client->id }}" name="password" type="password" class="mt-1 block w-3/4"
                placeholder="{{ __('Password') }}" />
            <x-input-error :messages="$errors->{$client->id}->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {{ __('Delete Client') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
