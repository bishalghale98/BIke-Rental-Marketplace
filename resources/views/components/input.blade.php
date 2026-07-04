@props(['label' => null, 'name', 'id' => null, 'type' => 'text', 'error' => null, 'helper' => null])

@php
$id = $id ?? $name;
$hasError = $error || $errors->has($name);
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        @class([
            'block w-full rounded-lg border text-sm transition-all duration-150 px-3 py-2.5 bg-white dark:bg-zinc-800 dark:text-zinc-100 placeholder:text-zinc-400',
            'border-zinc-300 dark:border-zinc-600 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20' => !$hasError,
            'border-danger-500 dark:border-danger-500 focus:border-danger-500 focus:ring-2 focus:ring-danger-500/20' => $hasError,
        ])
        {{ $attributes }}
    >
    @if ($helper && !$hasError)
        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $helper }}</p>
    @endif
    @if ($hasError)
        <p class="text-sm text-danger-600 dark:text-danger-500">{{ $error ?? $errors->first($name) }}</p>
    @endif
</div>
