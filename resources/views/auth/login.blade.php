@extends('layouts.public')
@section('title', 'Log In')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <x-card>
        <h2 class="text-xl font-semibold text-gray-900">Log In</h2>
        <p class="mt-1 text-sm text-gray-600">Welcome back to {{ config('app.name') }}.</p>

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <x-input label="Email" name="email" type="email" required value="{{ old('email') }}" />
            <x-input label="Password" name="password" type="password" required />
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    Remember me
                </label>
                <a href="#" class="text-sm text-gray-900 hover:underline">Forgot password?</a>
            </div>
            <x-button type="submit" class="w-full">Log In</x-button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-gray-900 font-medium hover:underline">Register</a>
        </p>
    </x-card>
</div>
@endsection
