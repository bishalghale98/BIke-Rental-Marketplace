@extends('layouts.company')
@section('title', 'Booking Report')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Booking Report</h1>
        <p class="mt-1 text-gray-600">All bookings within a date range.</p>
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
    <a href="{{ route('company.reports.bookings.export', request()->only(['from', 'to'])) }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Download CSV</a>
</form>

<div class="mt-8 overflow-x-auto">
    <x-card>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-gray-500">
                    <th class="pb-3 font-medium">Booking #</th>
                    <th class="pb-3 font-medium">Bike</th>
                    <th class="pb-3 font-medium">Customer</th>
                    <th class="pb-3 font-medium">Dates</th>
                    <th class="pb-3 font-medium">Amount</th>
                    <th class="pb-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3"><a href="{{ route('company.bookings.show', $booking) }}" class="font-medium text-gray-900 hover:underline">{{ $booking->booking_number }}</a></td>
                        <td class="py-3 text-gray-600">{{ $booking->bike->name }}</td>
                        <td class="py-3 text-gray-600">{{ $booking->customer?->user?->name ?? 'N/A' }}</td>
                        <td class="py-3 text-xs text-gray-600">{{ $booking->start_date->format('M d, g:i A') }} &mdash; {{ $booking->end_date->format('M d, g:i A') }}</td>
                        <td class="py-3 font-medium text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</td>
                        <td class="py-3">
                            <x-badge :variant="match($booking->status->value) { 'completed' => 'green', 'cancelled', 'refunded', 'expired' => 'gray', 'picked_up' => 'blue', 'confirmed', 'pending_payment', 'deposit_paid' => 'yellow', default => 'gray' }">{{ ucfirst($booking->status->value) }}</x-badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">No bookings for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    @if ($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
