@props(['padding' => true, 'header' => null])

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    @if ($header)
        <div class="px-6 py-4 border-b border-gray-200">
            {{ $header }}
        </div>
    @endif
    <div @class(['px-6 py-5' => $padding])>
        {{ $slot }}
    </div>
</div>
