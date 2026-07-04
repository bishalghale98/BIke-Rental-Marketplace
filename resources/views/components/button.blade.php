@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'disabled' => false, 'href' => null, 'loading' => false])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 cursor-pointer select-none';
$variantClasses = [
    'primary' => 'text-white bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 active:scale-[0.98] shadow-sm',
    'secondary' => 'text-zinc-700 bg-white border border-zinc-300 hover:bg-zinc-50 hover:border-zinc-400 focus:ring-primary-500 active:scale-[0.98]',
    'danger' => 'text-white bg-danger-600 hover:bg-danger-700 focus:ring-danger-500 active:scale-[0.98] shadow-sm',
    'ghost' => 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 focus:ring-zinc-500',
];
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm gap-1.5',
    'md' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2',
];
$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
$classes .= $disabled ? ' opacity-50 cursor-not-allowed pointer-events-none' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" @class([$classes, 'inline-flex']) {{ $attributes }}>
        @if ($loading)
            <svg class="animate-spin -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) @class([$classes]) {{ $attributes }}>
        @if ($loading)
            <svg class="animate-spin -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
        @endif
        {{ $slot }}
    </button>
@endif
