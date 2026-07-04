@extends('layouts.customer')
@section('title', 'Extension Requests')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Extension Requests</h1>

    @if ($requests->isEmpty())
        <div class="p-6 bg-white rounded-xl border border-gray-200 text-center">
            <p class="text-gray-500">No extension requests yet.</p>
            <a href="{{ route('customer.bookings.index') }}" class="mt-2 inline-block text-sm text-gray-900 font-medium hover:underline">View your bookings</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($requests as $request)
                <div class="p-4 bg-white rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $request->booking->bike->name }}</p>
                            <p class="text-sm text-gray-600">Current end: {{ $request->booking->effective_end_date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600">Requested end: {{ $request->requested_end_date->format('M d, Y') }}</p>
                            @if ($request->reason)<p class="text-sm text-gray-500 mt-1">Reason: {{ $request->reason }}</p>@endif
                        </div>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @if ($request->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($request->status === 'approved') bg-green-100 text-green-700
                            @else bg-red-100 text-red-600 @endif
                        ">{{ ucfirst($request->status) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
