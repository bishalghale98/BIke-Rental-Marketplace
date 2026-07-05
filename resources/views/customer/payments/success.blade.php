@extends('layouts.customer')
@section('title', 'Payment Successful')

@section('content')
<div class="max-w-lg mx-auto text-center">
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Payment Successful!</h1>
        <p class="mt-2 text-gray-600">Your deposit for booking #{{ $booking->booking_number }} has been received.</p>

        <div class="mt-6 space-y-3">
            <p class="text-sm text-gray-500">
                <span class="font-medium">Bike:</span> {{ $booking->bike->name }}
            </p>
            <p class="text-sm text-gray-500">
                <span class="font-medium">Dates:</span> {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}
            </p>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('customer.bookings.show', $booking) }}" class="px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors">
                View Booking
            </a>
            <a href="{{ route('customer.bookings.index') }}" class="px-6 py-2.5 border border-zinc-300 text-zinc-700 rounded-lg text-sm font-medium hover:bg-zinc-50 transition-colors">
                My Bookings
            </a>
        </div>
    </div>
</div>
@endsection
