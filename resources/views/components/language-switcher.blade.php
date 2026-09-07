{{-- Language switcher. Persists selection in the session via the locale.switch route. --}}
@php
    $currentLocale = app()->getLocale();
    $locales = [
        'id' => 'ID',
        'en' => 'EN',
    ];
@endphp

<x-dropdown align="right" width="40">
    <x-slot name="trigger">
        <button
            class="inline-flex items-center gap-1 px-2 py-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
            title="{{ __('Language') }}">
            <span
                class="text-m font-semibold uppercase">{{ $locales[$currentLocale] ?? strtoupper($currentLocale) }}</span>
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $code => $label)
            <x-dropdown-link :href="route('locale.switch', $code)" class="{{ $currentLocale === $code ? 'font-semibold' : '' }}">
                {{ $label }}
            </x-dropdown-link>
        @endforeach
    </x-slot>
</x-dropdown>
