@props(['label' => null, 'name', 'id' => null, 'options' => [], 'placeholder' => null, 'value' => null, 'error' => null])

@php
$id = $id ?? $name;
$hasError = $error || $errors->has($name);
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        @class([
            'block w-full rounded-lg border text-sm transition-all duration-150 px-3 py-2.5 bg-white dark:bg-zinc-800 dark:text-zinc-100',
            'border-zinc-300 dark:border-zinc-600 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20' => !$hasError,
            'border-danger-500 dark:border-danger-500 focus:border-danger-500 focus:ring-2 focus:ring-danger-500/20' => $hasError,
        ])
        {{ $attributes }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optValue => $label)
            <option value="{{ $optValue }}" @selected(old($name, $value) == $optValue)>{{ $label }}</option>
        @endforeach
    </select>
    @if ($hasError)
        <p class="text-sm text-danger-600 dark:text-danger-500">{{ $error ?? $errors->first($name) }}</p>
    @endif
</div>
