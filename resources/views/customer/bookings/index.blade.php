@extends('layouts.customer')
@section('title', 'My Bookings')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
        <p class="mt-1 text-gray-600">View and manage your rental bookings.</p>
    </div>
</div>

@if (session('success'))
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
@endif

@if ($bookings->isEmpty())
    <div class="mt-6">
        <x-card>
            <p class="text-gray-500 text-center py-8">No bookings yet. <a href="{{ route('bikes.index') }}" class="text-gray-900 font-medium hover:underline">Browse bikes to get started</a>.</p>
        </x-card>
    </div>
@else
    <div class="mt-6 space-y-4">
        @foreach ($bookings as $booking)
            <a href="{{ route('customer.bookings.show', $booking) }}" class="block bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        @if ($booking->bike->images->where('is_primary', true)->first())
                            <img src="{{ asset('storage/' . $booking->bike->images->where('is_primary', true)->first()->image_path) }}" class="w-16 h-16 rounded-lg object-cover">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">No img</div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $booking->bike->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $booking->bike->brand }} {{ $booking->bike->model }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $booking->start_date->format('M d, Y g:i A') }} &mdash; {{ $booking->end_date->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <x-badge :variant="match($booking->status->value) { 'completed' => 'green', 'cancelled', 'refunded', 'expired' => 'gray', 'picked_up' => 'blue', 'confirmed', 'pending_payment', 'deposit_paid' => 'yellow', default => 'gray' }">{{ ucfirst($booking->status->value) }}</x-badge>
                        <p class="mt-2 text-sm font-semibold text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</p>
                        @if ($booking->status->value === 'completed' && !$booking->review)
                            <span class="mt-1 inline-block text-xs font-medium text-gray-900 hover:underline">Write Review</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if ($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
@endif
@endsection
