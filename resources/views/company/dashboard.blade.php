@extends('layouts.company')
@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Company Dashboard</h1>
<p class="mt-1 text-gray-600">Manage your bike listings and rentals.</p>

<div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-stat-card label="Total Revenue" value="NPR 0" />
    <x-stat-card label="Total Bikes" value="0" />
    <x-stat-card label="Available Bikes" value="0" />
    <x-stat-card label="Active Bookings" value="0" />
</div>
@endsection
