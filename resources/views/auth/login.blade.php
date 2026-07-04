@extends('layouts.public')
@section('title', 'Log In')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Welcome back</h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Sign in to your {{ config('app.name') }} account</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-danger-50 dark:bg-danger-500/10 border border-danger-200 dark:border-danger-800 rounded-xl text-sm text-danger-700 dark:text-danger-400">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <x-input label="Email" name="email" type="email" required value="{{ old('email') }}" placeholder="you@example.com" />
                <x-input label="Password" name="password" type="password" required placeholder="Enter your password" />
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-zinc-300 dark:border-zinc-600 text-primary-600 focus:ring-primary-500">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline">Forgot password?</a>
                </div>
                <x-button type="submit" class="w-full">Sign in</x-button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline">Create one</a>
        </p>
    </div>
</div>
@endsection
