@extends('layouts.public')
@section('title', 'Register')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <x-card>
        <h2 class="text-xl font-semibold text-gray-900">Create an Account</h2>
        <p class="mt-1 text-sm text-gray-600">Join {{ config('app.name') }} today.</p>

        <div class="mt-6 grid grid-cols-2 gap-4">
            <a href="#" class="block text-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                <div class="text-2xl mb-1">👤</div>
                Register as Customer
            </a>
            <a href="#" class="block text-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                <div class="text-2xl mb-1">🏢</div>
                Register as Company
            </a>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:underline">Log in</a>
        </p>
    </x-card>
</div>
@endsection
