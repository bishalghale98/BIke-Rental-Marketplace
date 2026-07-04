@extends('layouts.company')
@section('title', 'Revenue Report')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Revenue Report</h1>
        <p class="mt-1 text-gray-600">Daily revenue breakdown.</p>
    </div>
    <a href="{{ route('company.reports.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; All Reports</a>
</div>

<form method="GET" class="mt-6 flex items-end gap-4">
    <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">From</label>
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
    </div>
    <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">To</label>
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
    </div>
    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800">Filter</button>
    <a href="{{ route('company.reports.revenue.export', request()->only(['from', 'to'])) }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Download CSV</a>
</form>

<div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
    <x-stat-card label="Total Revenue" value="NPR {{ number_format($summary?->total_revenue ?? 0, 2) }}" />
    <x-stat-card label="Total Bookings" :value="$summary?->total_bookings ?? 0" />
    <x-stat-card label="Avg per Booking" value="NPR {{ number_format($summary?->avg_revenue ?? 0, 2) }}" />
</div>

<div class="mt-8 overflow-x-auto">
    <x-card>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-gray-500">
                    <th class="pb-3 font-medium">Date</th>
                    <th class="pb-3 font-medium">Bookings</th>
                    <th class="pb-3 font-medium">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($daily as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 text-gray-900">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                        <td class="py-3 text-gray-600">{{ $row->bookings }}</td>
                        <td class="py-3 font-medium text-gray-900">NPR {{ number_format($row->revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">No data for this period.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="border-t border-gray-200 font-semibold text-gray-900">
                    <td class="pt-3">Total</td>
                    <td class="pt-3">{{ $daily->sum('bookings') }}</td>
                    <td class="pt-3">NPR {{ number_format($daily->sum('revenue'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </x-card>
</div>
@endsection
