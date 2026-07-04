@extends('layouts.company')
@section('title', 'Extension Requests')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Extension Requests</h1>

    @if ($requests->isEmpty())
        <div class="p-6 bg-white rounded-xl border border-gray-200 text-center">
            <p class="text-gray-500">No extension requests.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($requests as $request)
                <div class="p-4 bg-white rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $request->booking->bike->name }}</p>
                            <p class="text-sm text-gray-600">Customer: {{ $request->customer->user->name }}</p>
                            <p class="text-sm text-gray-600">Current end: {{ $request->booking->effective_end_date->format('M d, Y g:i A') }}</p>
                            <p class="text-sm text-gray-600">Requested end: {{ $request->requested_end_date->format('M d, Y g:i A') }}</p>
                            @if ($request->reason)<p class="text-sm text-gray-500 mt-1">Reason: {{ $request->reason }}</p>@endif
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            @if ($request->status === 'pending')
                                <form method="POST" action="{{ route('company.extensions.approve', $request) }}">
                                    @csrf
                                    <x-button type="submit" class="text-sm">Approve</x-button>
                                </form>
                                <form method="POST" action="{{ route('company.extensions.deny', $request) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Deny</button>
                                </form>
                            @else
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if ($request->status === 'approved') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-600 @endif
                                ">{{ ucfirst($request->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
