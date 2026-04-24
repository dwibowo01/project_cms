@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 group
          {{ $active
              ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
    {{-- Icon slot --}}
    <span
        class="flex-shrink-0 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' }}">
        {{ $icon }}
    </span>
    {{-- Label hidden when sidebar is collapsed --}}
    <span x-show="!collapsed" x-transition.opacity class="truncate">
        {{ $slot }}
    </span>
</a>
