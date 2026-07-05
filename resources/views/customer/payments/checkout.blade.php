@extends('layouts.customer')
@section('title', 'Checkout - Booking #' . $booking->booking_number)

@section('content')
<div class="max-w-2xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('customer.bookings.index') }}" class="hover:text-gray-900">My Bookings</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Checkout</span>
    </nav>

    <h1 class="text-2xl font-bold text-gray-900">Complete Your Payment</h1>
    <p class="mt-1 text-gray-600">Pay the deposit to confirm your booking.</p>

    <div class="mt-6 space-y-6">
        <x-card>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Summary</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Bike</span>
                    <span class="font-medium">{{ $booking->bike->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Start Date</span>
                    <span class="font-medium">{{ $booking->start_date->format('M d, Y g:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">End Date</span>
                    <span class="font-medium">{{ $booking->end_date->format('M d, Y g:i A') }}</span>
                </div>
                <hr>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Rental Price</span>
                    <span class="font-bold text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </x-card>

        <x-card>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Breakdown</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-base">
                    <span class="text-gray-600">Deposit ({{ config('marketplace.deposit_percentage') }}%)</span>
                    <span class="font-bold text-primary-600">NPR {{ number_format($booking->deposit_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-base">
                    <span class="text-gray-600">Pay at Pickup (remaining)</span>
                    <span class="font-medium text-gray-900">NPR {{ number_format($booking->remaining_amount, 2) }}</span>
                </div>
                <hr>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total</span>
                    <span class="font-bold text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </x-card>

        <x-card>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Choose Payment Method</h3>
            <div class="space-y-3">
                <form method="POST" action="{{ route('customer.payment.pay', ['booking' => $booking, 'gateway' => 'khalti']) }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 border-2 border-purple-200 hover:border-purple-400 rounded-xl text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="16" fill="#6B21A8"/><text x="16" y="20" text-anchor="middle" fill="white" font-size="12" font-weight="bold">K</text></svg>
                        Pay with Khalti
                    </button>
                </form>

                <form method="POST" action="{{ route('customer.payment.pay', ['booking' => $booking, 'gateway' => 'esewa']) }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 border-2 border-green-200 hover:border-green-400 rounded-xl text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="16" fill="#16A34A"/><text x="16" y="20" text-anchor="middle" fill="white" font-size="10" font-weight="bold">eS</text></svg>
                        Pay with eSewa
                    </button>
                </form>
            </div>
            <p class="mt-4 text-xs text-gray-400 text-center">You have {{ config('marketplace.payment_timeout_minutes') }} minutes to complete payment before the booking expires.</p>
        </x-card>
    </div>
</div>
@endsection
