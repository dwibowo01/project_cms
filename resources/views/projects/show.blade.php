<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Project') }}: {{ $project->project_no }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('projects.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
                <a href="{{ route('projects.edit', $project) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <x-danger-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-project-deletion-{{ $project->id }}')">
                    {{ __('Delete') }}
                </x-danger-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 text-sm">
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Project No.') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $project->project_no }}</span></p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Project Name') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $project->project_name }}</span></p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Client Name') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $project->client->client_name ?: $project->client->client_no }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Project Type') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $project->project_type->value }}
                                    ({{ $project->project_type->code() }})</span></p>
                        </div>
                        <div class="space-y-1">
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Status') }}</span><span
                                    class="inline-block w-3">:</span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ $project->status->value }}
                                </span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Docking Start Date') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $project->docking_start_date?->format('d M Y') }}</span>
                            </p>
                            <p><span
                                    class="inline-block w-36 font-semibold text-gray-700 dark:text-gray-300">{{ __('Docking End Date') }}</span><span
                                    class="inline-block w-3">:</span><span>{{ $project->docking_end_date?->format('d M Y') ?? '-' }}</span>
                            </p>
                        </div>
                    </div>

                    @if ($project->description)
                        <div class="mb-2">
                            <p class="font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('Description') }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($project->quotations->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Penawaran') }}</h3>
                        <div class="overflow-x-auto">
                            <table
                                class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Nomor Penawaran') }}</th>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Tanggal Penawaran') }}</th>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 text-right">
                                            {{ __('Total Penawaran') }}</th>
                                        <th scope="col" class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($project->quotations as $quotation)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                {{ $quotation->quote_no }}
                                                @if ($quotation->revision > 0)
                                                    <span
                                                        class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                                        {{ __('Rev.') }} {{ $quotation->revision }}
                                                    </span>
                                                @endif
                                                @if ($quotation->isLatestRevision())
                                                    <span
                                                        class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                                                        {{ __('Latest') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                {{ $quotation->quotation_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right">
                                                Rp {{ number_format($quotation->grand_total, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right">
                                                <a href="{{ route('quotations.show', $quotation) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($project->confirmOrders->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Confirm Orders') }}
                        </h3>
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            @foreach ($project->confirmOrders as $confirmOrder)
                                <li class="flex items-center justify-between py-2">
                                    <span>
                                        {{ $confirmOrder->confirm_order_no }}
                                        <span class="text-gray-400">&middot;</span>
                                        {{ __('Quotation') }} {{ $confirmOrder->quotation->quote_no }}
                                        <span class="text-gray-400">&middot;</span>
                                        {{ __('Tanggal Confirm Order') }}:
                                        {{ $confirmOrder->created_at?->format('d M Y') }}
                                    </span>
                                    <a href="{{ route('confirm-orders.show', $confirmOrder) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ __('View') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if ($project->invoices->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Tagihan') }}</h3>
                        <div class="overflow-x-auto">
                            <table
                                class="w-full border-collapse bg-white dark:bg-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Nomor Invoice') }}</th>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Tanggal Invoice') }}</th>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Jenis Invoice') }}</th>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 text-right">
                                            {{ __('Jumlah') }}</th>
                                        <th scope="col"
                                            class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Status') }}</th>
                                        <th scope="col" class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($project->invoices as $invoice)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $invoice->invoice_no }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                {{ $invoice->invoice_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                {{ $invoice->invoice_type->value }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right">
                                                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                        {{ $invoice->status === \App\Enums\InvoiceStatus::PAID
                                                            ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200'
                                                            : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200' }}">
                                                    {{ $invoice->status->value }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right">
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">{{ __('Project Team') }}</h3>
                        <a href="{{ route('project-teams.edit', $project) }}"
                            class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            {{ __('Manage Team') }}
                        </a>
                    </div>
                    @if ($project->manager || $project->members->isNotEmpty())
                        <div class="flex flex-wrap items-center gap-2 text-sm">
                            @if ($project->manager)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                                    {{ $project->manager->user->name }}
                                    <span class="text-indigo-500 dark:text-indigo-300">&middot;</span>
                                    {{ __('Project Manager Docking') }}
                                </span>
                            @endif
                            @foreach ($project->members as $member)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ $member->user->name }}
                                    <span class="text-gray-400">&middot;</span>
                                    {{ __('Member Docking') }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No team members yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('projects.partials.delete-project-modal', ['project' => $project])
</x-app-layout>
