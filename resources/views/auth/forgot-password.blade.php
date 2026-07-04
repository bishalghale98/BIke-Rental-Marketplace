@extends('layouts.public')
@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Forgot password?</h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">No worries, we'll send you a reset link.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 sm:p-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-800 rounded-xl text-sm text-success-700 dark:text-success-400">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <x-input label="Email address" name="email" type="email" required placeholder="you@example.com" />
                <x-button type="submit" class="w-full">Send reset link</x-button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline">Back to sign in</a>
        </p>
    </div>
</div>
@endsection
