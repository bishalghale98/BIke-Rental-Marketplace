@props(['padding' => true, 'header' => null, 'hover' => false, 'class' => ''])

<div @class([
    'bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700',
    'hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150' => $hover,
    $class,
])>
    @if ($header)
        <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
            {!! $header !!}
        </div>
    @endif
    <div @class(['px-5 py-5' => $padding])>
        {{ $slot }}
    </div>
</div>
