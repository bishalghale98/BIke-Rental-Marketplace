@extends('layouts.customer')
@section('title', 'Request Extension')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Request Extension</h1>
    <p class="text-sm text-gray-600 mb-6">Booking #{{ $booking->booking_number }} &mdash; {{ $booking->bike->name }}</p>

    <form method="POST" action="{{ route('customer.extensions.store', $booking) }}" class="space-y-4">
        @csrf
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Current end date</label>
            <p class="text-sm text-gray-900 font-semibold">{{ $booking->effective_end_date->format('M d, Y g:i A') }}</p>
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Requested end date</label>
            <input type="datetime-local" name="requested_end_date" required min="{{ $booking->effective_end_date->addHour()->format('Y-m-d\TH:i') }}" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            @error('requested_end_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Reason (optional)</label>
            <textarea name="reason" rows="3" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900" maxlength="500"></textarea>
            @error('reason')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <x-button type="submit">Submit Request</x-button>
    </form>
</div>
@endsection
