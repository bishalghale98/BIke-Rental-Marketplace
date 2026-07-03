@props(['label' => null, 'name', 'id' => null, 'type' => 'text', 'error' => null])

@php
$id = $id ?? $name;
$hasError = $error || $errors->has($name);
@endphp

<div class="space-y-1">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        @class([
            'block w-full rounded-lg border text-sm transition-colors px-3 py-2',
            'border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900' => !$hasError,
            'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' => $hasError,
        ])
        {{ $attributes }}
    >
    @if ($hasError)
        <p class="text-sm text-red-600">{{ $error ?? $errors->first($name) }}</p>
    @endif
</div>
