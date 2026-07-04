@extends('layouts.company')
@section('title', 'Booking #' . $booking->booking_number)

@section('content')
<div class="max-w-4xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('company.bookings.index') }}" class="hover:text-gray-900">Bookings</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">#{{ $booking->booking_number }}</span>
    </nav>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="md:col-span-3 space-y-6">
            <x-card header="<div class='flex items-center justify-between'><h3 class='text-lg font-medium text-gray-900'>Booking Details</h3><x-badge :variant='$booking->status->value === \"completed\" ? \"green\" : ($booking->status->value === \"cancelled\" ? \"gray\" : ($booking->status->value === \"ongoing\" ? \"blue\" : ($booking->status->value === \"confirmed\" ? \"yellow\" : \"gray\")))'>{{ ucfirst($booking->status->value) }}</x-badge></div>">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Booking Number</p>
                        <p class="font-medium text-gray-900">{{ $booking->booking_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-medium text-gray-900">{{ $booking->customer?->user?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Start Date</p>
                        <p class="font-medium text-gray-900">{{ $booking->start_date->format('M d, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">End Date</p>
                        <p class="font-medium text-gray-900">{{ $booking->end_date->format('M d, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total Amount</p>
                        <p class="font-bold text-lg text-gray-900">NPR {{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($booking->status->value) }}</p>
                    </div>
                </div>

                @if ($booking->status->value === 'cancelled')
                    <hr class="my-4">
                    <div class="text-sm">
                        <p class="text-gray-500">Cancellation Reason</p>
                        <p class="font-medium text-gray-900">{{ $booking->cancellation_reason ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400 mt-1">Cancelled by {{ $booking->cancelled_by }} on {{ $booking->cancelled_at?->format('M d, Y g:i A') }}</p>
                    </div>
                @endif
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Bike</h3>">
                <div class="flex items-center gap-4">
                    @if ($booking->bike->images->where('is_primary', true)->first())
                        <img src="{{ asset('storage/' . $booking->bike->images->where('is_primary', true)->first()->image_path) }}" class="w-20 h-20 rounded-lg object-cover">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">No img</div>
                    @endif
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $booking->bike->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $booking->bike->brand }} {{ $booking->bike->model }} ({{ $booking->bike->year }})</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $booking->bike->fuel_type }} &middot; {{ $booking->bike->transmission }}</p>
                    </div>
                </div>
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Customer</h3>">
                <div class="text-sm space-y-2">
                    <p><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $booking->customer?->user?->name ?? 'N/A' }}</span></p>
                    <p><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $booking->customer?->user?->email ?? 'N/A' }}</span></p>
                    <p><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $booking->customer?->user?->phone ?? 'N/A' }}</span></p>
                </div>
            </x-card>
        </div>

        <div class="md:col-span-2 space-y-6">
            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Update Status</h3>">
                <form method="POST" action="{{ route('company.bookings.update-status', $booking) }}">
                    @csrf
                    <div class="space-y-3">
                        @php
                            $transitions = match ($booking->status->value) {
                                'pending' => ['confirmed' => 'Confirm', 'cancelled' => 'Cancel'],
                                'confirmed' => ['ongoing' => 'Mark as Ongoing', 'cancelled' => 'Cancel'],
                                'ongoing' => ['completed' => 'Mark as Completed'],
                                default => [],
                            };
                        @endphp
                        @foreach ($transitions as $value => $label)
                            <button type="submit" name="status" value="{{ $value }}"
                                onclick="return confirm('{{ $label }} this booking?')"
                                class="w-full px-4 py-2 text-sm font-medium rounded-lg border text-left
                                    {{ $value === 'cancelled' ? 'text-red-600 border-red-200 hover:bg-red-50' : ($value === 'completed' ? 'text-green-600 border-green-200 hover:bg-green-50' : 'text-gray-700 border-gray-300 hover:bg-gray-50') }}">
                                {{ $label }}
                            </button>
                        @endforeach
                        @if (empty($transitions))
                            <p class="text-sm text-gray-500">No further actions available.</p>
                        @endif
                    </div>
                </form>
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Price Summary</h3>">
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
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
