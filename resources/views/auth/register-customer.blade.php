@extends('layouts.public')
@section('title', 'Register as Customer')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <x-card>
        <h2 class="text-xl font-semibold text-gray-900">Create Customer Account</h2>
        <p class="mt-1 text-sm text-gray-600">Start browsing and booking bikes.</p>
        <form method="POST" action="{{ route('register.customer') }}" class="mt-6 space-y-4">
            @csrf
            <x-input label="Full Name" name="name" type="text" required value="{{ old('name') }}" />
            <x-input label="Email" name="email" type="email" required value="{{ old('email') }}" />
            <x-input label="Phone (optional)" name="phone" type="text" value="{{ old('phone') }}" />
            <x-input label="Password" name="password" type="password" required />
            <x-input label="Confirm Password" name="password_confirmation" type="password" required />
            <x-button type="submit" class="w-full">Create Account</x-button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:underline">Log in</a>
        </p>
    </x-card>
</div>
@endsection
