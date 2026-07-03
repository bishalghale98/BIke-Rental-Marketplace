@props(['headers' => [], 'rows' => [], 'striped' => false])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        @if (count($headers) > 0)
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($rows as $index => $row)
                <tr @class(['bg-gray-50' => $striped && $index % 2 === 1])>
                    {{ $row }}
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center text-sm text-gray-500">
                        No data available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
