@extends('layouts.company')
@section('title', 'Bookings')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Bookings</h1>
        <p class="mt-1 text-gray-600">Manage customer bookings for your bikes.</p>
    </div>
</div>

@if (session('success'))
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
@endif

@if ($bookings->isEmpty())
    <div class="mt-6">
        <x-card>
            <p class="text-gray-500 text-center py-8">No bookings received yet.</p>
        </x-card>
    </div>
@else
    <div class="mt-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-gray-500">
                    <th class="pb-3 font-medium">Booking</th>
                    <th class="pb-3 font-medium">Bike</th>
                    <th class="pb-3 font-medium">Customer</th>
                    <th class="pb-3 font-medium">Dates</th>
                    <th class="pb-3 font-medium">Amount</th>
                    <th class="pb-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">
                            <a href="{{ route('company.bookings.show', $booking) }}" class="font-medium text-gray-900 hover:underline">{{ $booking->booking_number }}</a>
                        </td>
                        <td class="py-3 text-gray-600">{{ $booking->bike->name }}</td>
                        <td class="py-3 text-gray-600">{{ $booking->customer?->user?->name ?? 'N/A' }}</td>
                        <td class="py-3 text-gray-600">
                            <span class="text-xs">{{ $booking->start_date->format('M d, g:i A') }} &mdash; {{ $booking->end_date->format('M d, g:i A') }}</span>
                        </td>
                        <td class="py-3 font-medium text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</td>
                        <td class="py-3">
                            <x-badge :variant="match($booking->status->value) { 'completed' => 'green', 'cancelled', 'refunded', 'expired' => 'gray', 'picked_up' => 'blue', 'confirmed', 'pending_payment', 'deposit_paid' => 'yellow', default => 'gray' }">{{ ucfirst($booking->status->value) }}</x-badge>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
@endif
@endsection
