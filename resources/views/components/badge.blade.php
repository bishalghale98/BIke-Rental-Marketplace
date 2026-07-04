@props(['variant' => 'gray'])

@php
$variantClasses = [
    'gray' => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
    'green' => 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-500',
    'red' => 'bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-500',
    'yellow' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-500',
    'blue' => 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-500',
    'purple' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-500',
];
@endphp

<span @class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
    $variantClasses[$variant] ?? $variantClasses['gray'],
]) {{ $attributes }}>
    {{ $slot }}
</span>
