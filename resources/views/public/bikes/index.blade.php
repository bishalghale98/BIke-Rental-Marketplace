@extends('layouts.public')
@section('title', 'Browse Bikes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900">Browse Bikes</h1>
    <p class="mt-2 text-gray-600">Find the perfect bike for your next ride.</p>
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-card>
            <p class="text-gray-500 text-center py-12">No bikes listed yet. Check back soon.</p>
        </x-card>
    </div>
</div>
@endsection
