@extends('layouts.company')
@section('title', 'Reports')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Reports</h1>
<p class="mt-1 text-gray-600">Generate and download business reports.</p>

<div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
    <x-card>
        <a href="{{ route('company.reports.revenue') }}" class="block text-center py-6">
            <div class="text-3xl mb-3">💰</div>
            <h3 class="font-semibold text-gray-900">Revenue Report</h3>
            <p class="text-sm text-gray-500 mt-1">Daily revenue breakdown with date range filter</p>
        </a>
    </x-card>
    <x-card>
        <a href="{{ route('company.reports.bookings') }}" class="block text-center py-6">
            <div class="text-3xl mb-3">📋</div>
            <h3 class="font-semibold text-gray-900">Booking Report</h3>
            <p class="text-sm text-gray-500 mt-1">All bookings within a date range</p>
        </a>
    </x-card>
    <x-card>
        <a href="{{ route('company.reports.bikes') }}" class="block text-center py-6">
            <div class="text-3xl mb-3">🏍️</div>
            <h3 class="font-semibold text-gray-900">Bike Performance</h3>
            <p class="text-sm text-gray-500 mt-1">Per-bike booking and revenue stats</p>
        </a>
    </x-card>
</div>
@endsection
