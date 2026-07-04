@props(['headers' => [], 'rows' => [], 'striped' => false, 'search' => null, 'sortable' => false])

<div>
    @if ($search)
        <div class="mb-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search..." class="w-full rounded-lg border border-zinc-300 text-sm pl-10 pr-4 py-2 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            </div>
        </div>
    @endif
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            @if (count($headers) > 0)
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        @foreach ($headers as $header)
                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($rows as $index => $row)
                    <tr @class([
                        'bg-zinc-50/50 dark:bg-zinc-800/30' => $striped && $index % 2 === 1,
                        'hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors',
                    ])>
                        {{ $row }}
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-5 py-16 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <span>No data available</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
