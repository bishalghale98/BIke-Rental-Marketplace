@props(['href', 'active' => false, 'icon' => null])
<a href="{{ $href }}" @class([
    'flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-all duration-150',
    'bg-primary-50 text-primary-700 font-medium dark:bg-primary-500/10 dark:text-primary-400' => $active,
    'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800' => !$active,
])>
    @if ($icon)
        <span class="shrink-0 w-5 h-5">{!! $icon !!}</span>
    @endif
    {{ $slot }}
</a>
