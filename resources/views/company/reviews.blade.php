@extends('layouts.company')
@section('title', 'Reviews')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Customer Reviews</h1>
<p class="mt-1 text-gray-600">See what customers are saying about your bikes.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No reviews yet.</p>
    </x-card>
</div>
@endsection
