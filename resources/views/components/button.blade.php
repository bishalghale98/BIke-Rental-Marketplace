@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'disabled' => false, 'href' => null])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';
$variantClasses = [
    'primary' => 'text-white bg-gray-900 hover:bg-gray-800 focus:ring-gray-500',
    'secondary' => 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-gray-500',
    'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'ghost' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:ring-gray-500',
];
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];
$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
$classes .= $disabled ? ' opacity-50 cursor-not-allowed' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" @class([$classes, 'inline-flex']) {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) @class([$classes]) {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
