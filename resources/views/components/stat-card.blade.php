@props(['label', 'value', 'icon' => null, 'trend' => null, 'trendUp' => true])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $value }}</p>
            @if ($trend)
                <p @class([
                    'mt-1 text-sm inline-flex items-center gap-1',
                    'text-green-600' => $trendUp,
                    'text-red-600' => !$trendUp,
                ])>
                    @if ($trendUp)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    @endif
                    {{ $trend }}
                </p>
            @endif
        </div>
        @if ($icon)
            <div class="p-3 bg-gray-100 rounded-lg">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
