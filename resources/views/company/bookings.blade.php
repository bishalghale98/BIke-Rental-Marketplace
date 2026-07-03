@extends('layouts.company')
@section('title', 'Bookings')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Bookings</h1>
<p class="mt-1 text-gray-600">Manage customer bookings.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No bookings yet.</p>
    </x-card>
</div>
@endsection
