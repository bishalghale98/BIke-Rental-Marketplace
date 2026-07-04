@props(['label', 'value', 'icon' => null, 'trend' => null, 'trendUp' => true, 'color' => 'primary'])

@php
$colorClasses = [
    'primary' => 'bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400',
    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
    'danger' => 'bg-danger-50 text-danger-600 dark:bg-danger-500/10 dark:text-danger-400',
];
@endphp

<div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 transition-all duration-150 hover:shadow-md">
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $label }}</p>
            <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $value }}</p>
            @if ($trend)
                <p @class([
                    'mt-1 text-sm inline-flex items-center gap-1',
                    'text-success-600 dark:text-success-500' => $trendUp,
                    'text-danger-600 dark:text-danger-500' => !$trendUp,
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
            <div @class(['p-3 rounded-xl', $colorClasses[$color] ?? $colorClasses['primary']])>
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
