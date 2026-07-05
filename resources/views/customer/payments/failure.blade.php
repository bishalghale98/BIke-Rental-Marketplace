@extends('layouts.customer')
@section('title', 'Payment Failed')

@section('content')
<div class="max-w-lg mx-auto text-center">
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Payment Failed</h1>
        <p class="mt-2 text-gray-600">We couldn't process your payment. Please try again or choose a different payment method.</p>

        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('customer.payment.checkout', $booking) }}" class="px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors">
                Try Again
            </a>
            <a href="{{ route('customer.bookings.show', $booking) }}" class="px-6 py-2.5 border border-zinc-300 text-zinc-700 rounded-lg text-sm font-medium hover:bg-zinc-50 transition-colors">
                View Booking
            </a>
        </div>
    </div>
</div>
@endsection
