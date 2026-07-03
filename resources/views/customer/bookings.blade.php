@extends('layouts.customer')
@section('title', 'My Bookings')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
<p class="mt-1 text-gray-600">View and manage your rental bookings.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No bookings yet.</p>
    </x-card>
</div>
@endsection
