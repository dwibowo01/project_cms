@php
    $units = ['Lot', 'Day', 'Pcs', 'M2', 'M3', 'M', 'Kg', 'Set'];
    $selectedCategoryId = old('category_id', $categoryId);
    $selectedParentId = old('parent_id', $parent?->id ?? ($item->parent_id ?? ''));
    // Category is fixed once a parent is preselected or the item already exists, so a whole subtree can't end up split across categories.
    $categoryLocked = (isset($parent) && $parent) || isset($item);
@endphp

@if ($categoryLocked)
    <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div>
        <x-input-label for="category_id" :value="__('Category')" />
        @if ($categoryLocked)
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $categories->firstWhere('id', (int) $selectedCategoryId)?->name }}
            </p>
        @else
            <select id="category_id" name="category_id" required
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string) $selectedCategoryId === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        @endif
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="parent_id" :value="__('Parent Item')" />
        <select id="parent_id" name="parent_id"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="">{{ __('None (top level)') }}</option>
            @foreach ($parentOptions as $option)
                <option value="{{ $option->id }}"
                    {{ (string) $selectedParentId === (string) $option->id ? 'selected' : '' }}>
                    {{ str_repeat('— ', $option->depth) }}{{ $option->code }}.
                    {{ \Illuminate\Support\Str::limit($option->name, 60) }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
    </div>
</div>

<div class="mb-6">
    <x-input-label for="name" :value="__('Item Description')" />
    <textarea id="name" name="name" rows="3" required
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('name', $item->name ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
    <div>
        <x-input-label for="qty" :value="__('Qty')" />
        <x-text-input id="qty" class="block mt-1 w-full" type="number" step="1" min="0"
            name="qty" :value="old('qty', $item->qty ?? '')" />
        <x-input-error :messages="$errors->get('qty')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="unit" :value="__('Unit')" />
        <input list="unit-options" id="unit" name="unit" type="text"
            value="{{ old('unit', $item->unit ?? '') }}"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
        <datalist id="unit-options">
            @foreach ($units as $unit)
                <option value="{{ $unit }}"></option>
            @endforeach
        </datalist>
        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="unit_price" :value="__('Unit Price') . ' (Rp)'" />
        <x-text-input id="unit_price" class="block mt-1 w-full" type="number" step="0.01" min="0"
            name="unit_price" :value="old('unit_price', $item->unit_price ?? '')" />
        <x-input-error :messages="$errors->get('unit_price')" class="mt-2" />
    </div>
</div>
