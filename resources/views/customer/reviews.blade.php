@extends('layouts.customer')
@section('title', 'My Reviews')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">My Reviews</h1>
<p class="mt-1 text-gray-600">Reviews you've left for completed rentals.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No reviews yet.</p>
    </x-card>
</div>
@endsection
