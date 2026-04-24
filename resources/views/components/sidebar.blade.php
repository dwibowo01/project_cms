{{--
    Shrinkable sidebar component.
    Collapse state is persisted in localStorage via a $watch (no plugin required).
--}}
@php
    // On bare IP deployments, tenant context is passed via ?tenant= query param.
    // Append it to all links so navigation doesn't lose the tenant.
$appHost = parse_url(config('app.url'), PHP_URL_HOST);
$tenantSuffix = tenant() && filter_var($appHost, FILTER_VALIDATE_IP) ? '?tenant=' . tenant('id') : '';
@endphp
<div x-data="{
    collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
    tenantMenuOpen: false,
    marketingOpen: false
}" x-init="$watch('collapsed', val => localStorage.setItem('sidebar_collapsed', val))" :class="collapsed ? 'w-16' : 'w-64'"
    class="flex flex-col h-full bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-all duration-300 ease-in-out flex-shrink-0">

    {{-- Sidebar Header: logo + app name + collapse toggle --}}
    <div class="flex items-center h-16 px-3 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') . $tenantSuffix }}" class="flex items-center gap-3 min-w-0 flex-1">
            {{-- CMS logo inlined to avoid asset() URL issues on tenant subdomains --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32.28 30.7" class="w-8 h-8 flex-shrink-0"
                style="shape-rendering:geometricPrecision; fill-rule:evenodd; clip-rule:evenodd">
                <defs>
                    <radialGradient id="cms-grad0" gradientUnits="userSpaceOnUse"
                        gradientTransform="matrix(0.504281 -0 -0 0.999996 4 0)" cx="7.56" cy="15.35" r="25.33"
                        fx="7.56" fy="15.35">
                        <stop offset="0" style="stop-color:#4E3DE3" />
                        <stop offset="1" style="stop-color:#2A1771" />
                    </radialGradient>
                    <radialGradient id="cms-grad1" gradientUnits="userSpaceOnUse"
                        gradientTransform="matrix(0.536472 -0 -0 1.06383 11 -1)" cx="24.27" cy="15.96" r="23.81"
                        fx="24.27" fy="15.96">
                        <stop offset="0" style="stop-color:#D47F7F" />
                        <stop offset="1" style="stop-color:#960702" />
                    </radialGradient>
                </defs>
                <polygon fill="url(#cms-grad0)"
                    points="9.22,0.13 8.97,0.26 8.84,0.39 8.71,0.52 8.58,0.66 8.45,0.79 8.2,0.92 8.07,1.05 7.94,1.18 7.81,1.31 7.69,1.44 7.43,1.57 7.3,1.71 7.17,1.84 7.05,1.97 6.92,2.1 6.66,2.23 6.53,2.36 6.41,2.49 6.28,2.62 6.15,2.76 5.89,2.89 5.76,3.02 5.64,3.15 5.51,3.28 5.25,3.41 5.12,3.54 5,3.67 4.87,3.8 4.74,3.94 4.48,4.07 4.36,4.2 4.23,4.33 4.1,4.46 3.97,4.59 3.71,4.72 3.59,4.85 3.46,4.99 3.33,5.12 3.07,5.25 2.95,5.38 2.82,5.51 2.69,5.64 2.56,5.77 2.31,5.9 2.18,6.04 2.05,6.17 1.92,6.3 1.79,6.43 1.54,6.56 1.41,6.69 1.28,6.82 1.15,6.95 0.9,7.08 0.77,7.22 0.64,7.35 0.51,7.48 0.38,7.61 0.13,7.74 -0,7.87 -0,21.78 0.13,21.91 0.26,22.04 0.38,22.17 0.51,22.3 0.64,22.43 0.77,22.57 0.9,22.7 1.02,22.83 1.15,22.96 1.28,23.09 1.41,23.22 1.54,23.35 1.67,23.48 1.79,23.62 1.92,23.75 2.05,23.88 2.18,24.01 2.31,24.14 2.43,24.27 2.56,24.4 2.69,24.53 2.82,24.67 2.95,24.8 3.07,24.93 3.2,25.06 3.33,25.19 3.46,25.32 3.59,25.45 3.71,25.58 3.84,25.71 3.97,25.85 4.1,25.98 4.23,26.11 4.36,26.24 4.48,26.37 4.61,26.5 4.74,26.63 4.87,26.76 5,26.9 5.12,27.03 5.25,27.16 5.38,27.29 5.51,27.42 5.64,27.55 5.76,27.68 5.89,27.81 6.02,27.95 6.15,28.08 6.28,28.21 6.41,28.34 6.53,28.47 6.66,28.6 6.79,28.73 6.92,28.86 7.05,28.99 7.17,29.13 7.3,29.26 7.43,29.39 7.56,29.52 7.69,29.65 7.81,29.78 7.94,29.91 8.07,30.04 8.2,30.18 8.33,30.31 8.45,30.44 8.58,30.57 8.71,30.7 15.12,30.7 15.12,24.14 11.79,24.01 11.66,23.88 11.53,23.75 11.4,23.62 11.27,23.48 11.14,23.35 11.02,23.22 10.89,23.09 10.76,22.96 10.63,22.83 10.5,22.7 10.38,22.57 10.25,22.43 10.12,22.3 9.99,22.17 9.86,22.04 9.74,21.91 9.61,21.78 9.48,21.65 9.35,21.52 9.22,21.39 9.1,21.25 8.97,21.12 8.84,20.99 8.71,20.86 8.58,20.73 8.45,20.6 8.33,20.47 8.2,20.34 8.07,20.2 7.94,20.07 7.81,19.94 7.69,19.81 7.56,19.68 7.43,19.55 7.3,19.42 7.17,19.29 7.17,11.41 7.3,11.28 7.56,11.15 7.69,11.02 7.81,10.89 8.07,10.76 8.2,10.63 8.33,10.5 8.45,10.36 8.71,10.23 8.84,10.1 9.1,9.97 9.22,9.84 9.35,9.71 9.48,9.58 9.74,9.45 9.86,9.31 9.99,9.18 10.25,9.05 10.38,8.92 10.5,8.79 10.76,8.66 10.89,8.53 11.02,8.4 11.27,8.27 11.4,8.13 11.53,8 11.79,7.87 11.91,7.74 12.04,7.61 12.3,7.48 12.43,7.35 12.55,7.22 12.81,7.08 12.94,6.95 13.07,6.82 13.19,6.69 15.12,6.56 15.12,0 9.35,0" />
                <polygon fill="url(#cms-grad1)"
                    points="17.17,6.56 19.09,6.69 19.22,6.82 19.34,6.95 19.47,7.08 19.73,7.22 19.86,7.35 19.98,7.48 20.24,7.61 20.37,7.74 20.5,7.87 20.75,8 20.88,8.13 21.01,8.27 21.14,8.4 21.39,8.53 21.52,8.66 21.78,8.79 21.91,8.92 22.03,9.05 22.16,9.18 22.42,9.31 22.55,9.45 22.8,9.45 22.8,9.71 23.06,9.84 23.19,9.97 23.44,10.1 23.57,10.23 23.7,10.36 23.83,10.5 24.08,10.63 24.21,10.76 24.47,10.89 24.6,11.02 24.72,11.15 24.85,11.28 25.11,11.41 25.24,11.94 17.29,11.94 17.17,12.07 17.17,18.63 17.29,18.76 25.25,18.76 25.11,19.29 24.98,19.42 24.85,19.55 24.72,19.68 24.6,19.81 24.47,19.94 24.34,20.07 24.21,20.2 24.08,20.34 23.95,20.47 23.83,20.6 23.7,20.73 23.57,20.86 23.44,20.99 23.31,21.12 23.19,21.25 23.06,21.39 22.93,21.52 22.8,21.65 22.67,21.78 22.55,21.91 22.42,22.04 22.29,22.17 22.16,22.3 22.03,22.43 21.91,22.57 21.78,22.7 21.65,22.83 21.52,22.96 21.39,23.09 21.26,23.22 21.14,23.35 21.01,23.48 20.75,23.62 20.75,23.88 20.5,23.88 20.5,24.14 17.17,24.14 17.17,30.7 23.57,30.7 23.7,30.57 23.83,30.44 23.95,30.31 24.08,30.18 24.21,30.04 24.34,29.91 24.47,29.78 24.6,29.65 24.72,29.52 24.85,29.39 24.98,29.26 25.11,29.13 25.24,28.99 25.36,28.86 25.49,28.73 25.62,28.6 25.75,28.47 25.88,28.34 26,28.21 26.13,28.08 26.26,27.95 26.39,27.81 26.52,27.68 26.64,27.55 26.77,27.42 26.9,27.29 27.03,27.16 27.16,27.03 27.29,26.9 27.41,26.76 27.54,26.63 27.67,26.5 27.8,26.37 27.93,26.24 28.05,26.11 28.18,25.98 28.31,25.85 28.44,25.71 28.57,25.58 28.69,25.45 28.82,25.32 28.95,25.19 29.08,25.06 29.21,24.93 29.34,24.8 29.46,24.67 29.59,24.53 29.72,24.4 29.85,24.27 29.98,24.14 30.1,24.01 30.23,23.88 30.36,23.75 30.49,23.62 30.62,23.48 30.74,23.35 30.87,23.22 31,23.09 31.13,22.96 31.26,22.83 31.38,22.7 31.51,22.57 31.64,22.43 31.77,22.3 31.9,22.17 32.03,22.04 32.15,21.91 32.28,21.78 32.28,7.87 32.03,7.87 32.03,7.61 31.77,7.48 31.64,7.35 31.51,7.22 31.38,7.08 31.13,6.95 31,6.82 30.87,6.69 30.74,6.56 30.49,6.43 30.36,6.3 30.23,6.17 30.1,6.04 29.85,5.9 29.72,5.77 29.59,5.64 29.46,5.51 29.34,5.38 29.21,5.25 28.95,5.12 28.82,4.99 28.69,4.85 28.57,4.72 28.31,4.59 28.18,4.46 28.05,4.33 27.93,4.2 27.67,4.2 27.67,3.94 27.41,3.8 27.29,3.67 27.16,3.54 27.03,3.41 26.77,3.28 26.64,3.15 26.52,3.02 26.39,2.89 26.13,2.76 26,2.62 25.88,2.49 25.75,2.36 25.49,2.23 25.36,2.1 25.24,1.97 25.11,1.84 24.98,1.71 24.85,1.57 24.6,1.44 24.47,1.31 24.34,1.18 24.21,1.05 23.95,0.92 23.83,0.79 23.7,0.66 23.57,0.52 23.31,0.52 23.31,0.26 23.06,0.13 22.93,0 17.17,0" />
            </svg>
            <span x-show="!collapsed" x-transition.opacity class="text-lg font-bold truncate">
                {{ config('app.name') }}
            </span>
        </a>
        <button @click="collapsed = !collapsed"
            class="ml-auto p-1.5 rounded text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 transition flex-shrink-0"
            :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <svg x-show="!collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    {{-- Tenant Switcher --}}
    @auth
        @php
            $isSuperAdmin = auth()->user()->isSuperAdmin();
            $switchableTenants = $isSuperAdmin ? \App\Models\Tenant::all() : auth()->user()->tenants;
        @endphp
        @if ($switchableTenants->isNotEmpty())
            <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                <button @click="tenantMenuOpen = !tenantMenuOpen"
                    class="w-full flex items-center gap-2 px-2 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white transition"
                    :class="collapsed ? 'justify-center' : 'justify-between'"
                    :title="collapsed ? '{{ tenant() ? ucfirst(str_replace('-', ' ', tenant('id'))) : __('Central') }}' : ''">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-500 dark:text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span x-show="!collapsed" x-transition.opacity class="truncate">
                            {{ tenant() ? ucfirst(str_replace('-', ' ', tenant('id'))) : __('Central') }}
                        </span>
                    </div>
                    <svg x-show="!collapsed"
                        class="w-4 h-4 flex-shrink-0 text-gray-500 dark:text-gray-400 transition-transform"
                        :class="tenantMenuOpen ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="tenantMenuOpen && !collapsed" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="mt-1 ml-2 space-y-0.5" style="display: none;">
                    @if ($isSuperAdmin && tenant())
                        @php
                            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
                            $centralUrl = filter_var($appHost, FILTER_VALIDATE_IP)
                                ? config('app.url') . '/dashboard'
                                : request()->getScheme() .
                                    '://' .
                                    collect(config('tenancy.central_domains'))->first(fn($d) => str_contains($d, '.')) .
                                    '/dashboard';
                        @endphp
                        <a href="{{ $centralUrl }}"
                            class="block px-3 py-1.5 text-sm rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white transition">
                            {{ __('Central') }}
                        </a>
                    @endif
                    @foreach ($switchableTenants as $switchTenant)
                        <a href="{{ route('tenant.switch', $switchTenant) }}"
                            class="block px-3 py-1.5 text-sm rounded-md hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white transition
                                   {{ tenant()?->id === $switchTenant->id ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            {{ ucfirst(str_replace('-', ' ', $switchTenant->id)) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        {{-- Dashboard --}}
        <x-sidebar-nav-item href="{{ route('dashboard') . $tenantSuffix }}" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </x-slot>
            {{ __('Dashboard') }}
        </x-sidebar-nav-item>

        @if (!tenant())
            {{-- Tenants --}}
            <x-sidebar-nav-item href="{{ route('tenants.index') }}" :active="request()->routeIs('tenants.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </x-slot>
                {{ __('Tenants') }}
            </x-sidebar-nav-item>
            {{-- Roles --}}
            <x-sidebar-nav-item href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </x-slot>
                {{ __('Roles') }}
            </x-sidebar-nav-item>
        @endif

        @if (tenant('id') === 'docking')
            {{-- Clients --}}
            <x-sidebar-nav-item href="" :active="request()->routeIs('')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </x-slot>
                {{ __('Clients') }}
            </x-sidebar-nav-item>

            {{-- Marketing group with sub-items (only on demo tenant) --}}
            <div>
                <button @click="marketingOpen = !marketingOpen"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150
                           text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    :class="collapsed ? 'justify-center' : ''" :title="collapsed ? '{{ __('Marketing') }}' : ''">
                    <span class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </span>
                    <span x-show="!collapsed" x-transition.opacity
                        class="flex-1 truncate text-left">{{ __('Marketing') }}</span>
                    <svg x-show="!collapsed" class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                        :class="marketingOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="marketingOpen && !collapsed" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mt-1 ml-4 pl-3 border-l border-gray-200 dark:border-gray-700 space-y-0.5"
                    style="display: none;">

                    {{-- Quotation --}}
                    <a href=""
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150
                               text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Quotation') }}
                    </a>

                    {{-- Confirm Order --}}
                    <a href=""
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150
                               text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Confirm Order') }}
                    </a>

                    {{-- Launch Order --}}
                    <a href=""
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150
                               text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        {{ __('Launch Order') }}
                    </a>

                    {{-- Invoice --}}
                    <a href=""
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150
                               text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ __('Invoice') }}
                    </a>

                </div>
            </div>

            {{-- Project Management --}}
            <x-sidebar-nav-item href="" :active="request()->routeIs('')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-6a2 2 0 012-2h6M9 17H5a2 2 0 00-2 2v3m14-6h2a2 2 0 012 2v3m-6-6h6" />
                    </svg>
                </x-slot>
                {{ __('Project Management') }}
            </x-sidebar-nav-item>
        @endif

        @if (tenant())
            {{-- Users --}}
            <x-sidebar-nav-item href="{{ route('users.index') . $tenantSuffix }}" :active="request()->routeIs('users.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </x-slot>
                {{ __('Users') }}
            </x-sidebar-nav-item>

            {{-- Roles --}}
            <x-sidebar-nav-item href="{{ route('roles.index') . $tenantSuffix }}" :active="request()->routeIs('roles.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </x-slot>
                {{ __('Roles') }}
            </x-sidebar-nav-item>
        @endif



    </nav>

</div>
