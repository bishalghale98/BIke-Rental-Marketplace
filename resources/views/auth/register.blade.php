@extends('layouts.public')
@section('title', 'Register')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <x-card>
        <h2 class="text-xl font-semibold text-gray-900">Create an Account</h2>
        <p class="mt-1 text-sm text-gray-600">Join {{ config('app.name') }} today.</p>

        <div class="mt-6 grid grid-cols-2 gap-4">
            <a href="{{ route('register.customer') }}" class="block text-center px-4 py-6 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:border-gray-900 hover:bg-gray-50 transition-colors">
                <div class="text-3xl mb-2">👤</div>
                <div class="font-semibold text-gray-900">Customer</div>
                <div class="text-xs text-gray-500 mt-1">Browse and book bikes</div>
            </a>
            <a href="{{ route('register.company') }}" class="block text-center px-4 py-6 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:border-gray-900 hover:bg-gray-50 transition-colors">
                <div class="text-3xl mb-2">🏢</div>
                <div class="font-semibold text-gray-900">Company</div>
                <div class="text-xs text-gray-500 mt-1">List bikes and earn</div>
            </a>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:underline">Log in</a>
        </p>
    </x-card>
</div>
@endsection
