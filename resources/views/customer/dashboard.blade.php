@extends('layouts.customer')
@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
<p class="mt-1 text-gray-600">Manage your rentals and account.</p>

<div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-stat-card label="Upcoming Bookings" value="0" />
    <x-stat-card label="Active Rentals" value="0" />
    <x-stat-card label="Completed" value="0" />
    <x-stat-card label="Verification" value="Not Verified" />
</div>
@endsection
