@props(['href', 'active' => false])
<a href="{{ $href }}" @class([
    'block px-3 py-2 text-sm rounded-lg transition-colors',
    'bg-gray-100 text-gray-900 font-medium' => $active,
    'text-gray-600 hover:text-gray-900 hover:bg-gray-100' => !$active,
])>
    {{ $slot }}
</a>
