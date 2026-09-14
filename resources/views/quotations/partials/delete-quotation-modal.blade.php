<x-modal name="{{ 'confirm-quotation-deletion-' . $quotation->id }}" focusable>
    <form method="post" action="{{ route('quotations.destroy', $quotation) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete this quotation?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once this quotation is deleted, all of its items will also be permanently deleted. This action cannot be undone.') }}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {{ __('Delete Quotation') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
