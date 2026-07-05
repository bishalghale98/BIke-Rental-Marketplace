@extends('layouts.customer')
@section('title', 'Booking #' . $booking->booking_number)

@section('content')
<div class="max-w-3xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('customer.bookings.index') }}" class="hover:text-gray-900">My Bookings</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">#{{ $booking->booking_number }}</span>
    </nav>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="md:col-span-3 space-y-6">
            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Booking Details</h3>
                        @php
                            $badgeVariant = match($booking->status->value) {
                                'completed' => 'green',
                                'cancelled' => 'gray',
                                'refunded' => 'gray',
                                'picked_up' => 'blue',
                                'confirmed' => 'yellow',
                                'deposit_paid' => 'blue',
                                'pending_payment' => 'yellow',
                                'expired' => 'gray',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ ucfirst($booking->status->value) }}</x-badge>
                    </div>
                </x-slot:header>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Booking Number</p>
                        <p class="font-medium text-gray-900">{{ $booking->booking_number }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500">Start Date</p>
                            <p class="font-medium text-gray-900">{{ $booking->start_date->format('M d, Y g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">End Date</p>
                            <p class="font-medium text-gray-900">{{ $booking->end_date->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500">Duration</p>
                            <p class="font-medium text-gray-900">
                                @if ($booking->total_weeks > 0){{ $booking->total_weeks }} week(s) @endif
                                @if ($booking->total_days > 0){{ $booking->total_days }} day(s) @endif
                                @if ($booking->total_hours > 0){{ $booking->total_hours }} hour(s) @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Total Amount</p>
                            <p class="font-bold text-lg text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>

                    @if ($booking->status->value === 'cancelled')
                        <hr>
                        <div>
                            <p class="text-gray-500">Cancellation Reason</p>
                            <p class="font-medium text-gray-900">{{ $booking->cancellation_reason ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 mt-1">Cancelled by {{ $booking->cancelled_by }} on {{ $booking->cancelled_at?->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Bike Information</h3>">
                <div class="flex items-center gap-4">
                    @if ($booking->bike->images->where('is_primary', true)->first())
                        <img src="{{ asset('storage/' . $booking->bike->images->where('is_primary', true)->first()->image_path) }}" class="w-20 h-20 rounded-lg object-cover">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">No img</div>
                    @endif
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $booking->bike->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $booking->bike->brand }} {{ $booking->bike->model }} ({{ $booking->bike->year }})</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $booking->bike->fuel_type }} &middot; {{ $booking->bike->transmission }} &middot; {{ $booking->bike->engine_capacity ?? '' }}</p>
                    </div>
                </div>
            </x-card>

            @if ($booking->bike->company)
                <x-card header="<h3 class='text-lg font-medium text-gray-900'>Company</h3>">
                    <div class="flex items-center gap-3">
                        @if ($booking->bike->company->logo)
                            <img src="{{ asset('storage/' . $booking->bike->company->logo) }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-400">{{ strtoupper(substr($booking->bike->company->company_name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $booking->bike->company->company_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $booking->bike->company->address ?? '' }}</p>
                        </div>
                    </div>
                </x-card>
            @endif
        </div>

        <div class="md:col-span-2">
            @if ($booking->status->value === 'completed' && !$booking->review)
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Leave a Review</h3>
                    <p class="text-sm text-gray-600 mb-4">Share your experience with this bike rental.</p>
                    <x-button href="{{ route('customer.reviews.create', $booking) }}" class="w-full justify-center">Write a Review</x-button>
                </x-card>
            @endif

            @if ($booking->status->value === 'pending_payment')
                <div class="border-2 border-yellow-200 bg-yellow-50 rounded-xl p-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-base font-semibold text-yellow-800">Payment Required</h3>
                    </div>
                    <p class="text-sm text-yellow-700">Pay the deposit to confirm your booking. This booking will expire if not paid within {{ config('marketplace.payment_timeout_minutes') }} minutes.</p>
                    <div class="bg-white rounded-lg p-3 space-y-2 text-sm border border-yellow-100">
                        <div class="flex justify-between"><span class="text-gray-500">Deposit ({{ config('marketplace.deposit_percentage') }}%)</span><span class="font-bold text-yellow-700">NPR {{ number_format($booking->deposit_amount, 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Pay at Pickup</span><span class="font-medium">NPR {{ number_format($booking->remaining_amount, 2) }}</span></div>
                    </div>
                    <div class="space-y-2">
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
                </div>
            @endif

            @if ($booking->status->value === 'confirmed')
                <div class="border-2 border-blue-200 bg-blue-50 rounded-xl p-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-base font-semibold text-blue-800">Pay Remaining Balance</h3>
                    </div>
                    <p class="text-sm text-blue-700">Pay the remaining amount before pickup to avoid any issues.</p>
                    <div class="bg-white rounded-lg p-3 text-sm border border-blue-100">
                        <div class="flex justify-between"><span class="text-gray-500">Remaining Amount</span><span class="font-bold text-blue-700">NPR {{ number_format($booking->remaining_amount, 2) }}</span></div>
                    </div>
                    <div class="space-y-2">
                        <form method="POST" action="{{ route('customer.payment.pay-remaining', ['booking' => $booking, 'gateway' => 'khalti']) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 border-2 border-purple-200 hover:border-purple-400 rounded-xl text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition-colors">
                                <svg class="w-6 h-6" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="16" fill="#6B21A8"/><text x="16" y="20" text-anchor="middle" fill="white" font-size="12" font-weight="bold">K</text></svg>
                                Pay with Khalti
                            </button>
                        </form>
                        <form method="POST" action="{{ route('customer.payment.pay-remaining', ['booking' => $booking, 'gateway' => 'esewa']) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 border-2 border-green-200 hover:border-green-400 rounded-xl text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                <svg class="w-6 h-6" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="16" fill="#16A34A"/><text x="16" y="20" text-anchor="middle" fill="white" font-size="10" font-weight="bold">eS</text></svg>
                                Pay with eSewa
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if (in_array($booking->status->value, ['pending_payment', 'deposit_paid', 'confirmed']))
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cancel Booking</h3>
                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}">
                        @csrf
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Reason (optional)</label>
                            <textarea name="cancellation_reason" rows="3" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                        </div>
                        <button type="submit" onclick="return confirm('Are you sure you want to cancel this booking?')" class="mt-4 w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Cancel Booking</button>
                    </form>
                </x-card>
            @endif

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Price Summary</h3>" class="mt-6">
                <div class="space-y-2 text-sm">
                    @if ($booking->total_weeks > 0)
                        <div class="flex justify-between"><span class="text-gray-500">{{ $booking->total_weeks }} week(s) x NPR {{ number_format($booking->weekly_price, 2) }}</span><span class="font-medium">NPR {{ number_format($booking->total_weeks * $booking->weekly_price, 2) }}</span></div>
                    @endif
                    @if ($booking->total_days > 0)
                        <div class="flex justify-between"><span class="text-gray-500">{{ $booking->total_days }} day(s) x NPR {{ number_format($booking->daily_price, 2) }}</span><span class="font-medium">NPR {{ number_format($booking->total_days * $booking->daily_price, 2) }}</span></div>
                    @endif
                    @if ($booking->total_hours > 0)
                        <div class="flex justify-between"><span class="text-gray-500">{{ $booking->total_hours }} hour(s) x NPR {{ number_format($booking->hourly_price, 2) }}</span><span class="font-medium">NPR {{ number_format($booking->total_hours * $booking->hourly_price, 2) }}</span></div>
                    @endif
                    <hr>
                    <div class="flex justify-between text-base"><span class="font-semibold text-gray-900">Total</span><span class="font-bold text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</span></div>
                    @if ($booking->deposit_paid_at)
                        <div class="flex justify-between text-green-600"><span>Deposit Paid</span><span>NPR {{ number_format($booking->deposit_amount, 2) }}</span></div>
                    @endif
                    @if ($booking->remaining_paid_at)
                        <div class="flex justify-between text-green-600"><span>Remaining Paid</span><span>NPR {{ number_format($booking->remaining_amount, 2) }}</span></div>
                    @endif
                    @if ($booking->late_fee)
                        <div class="flex justify-between text-red-600"><span>Late Fee</span><span>NPR {{ number_format($booking->late_fee, 2) }}</span></div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
