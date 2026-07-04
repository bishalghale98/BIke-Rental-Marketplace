@extends('layouts.public')
@section('title', 'Reset Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Set new password</h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Your new password must be different from previously used ones.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 sm:p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <x-input label="Email" name="email" type="email" value="{{ old('email', $email ?? '') }}" required placeholder="you@example.com" />
                <x-input label="New password" name="password" type="password" required minlength="8" placeholder="Min. 8 characters" />
                <x-input label="Confirm password" name="password_confirmation" type="password" required placeholder="Repeat your password" />
                <x-button type="submit" class="w-full">Reset password</x-button>
            </form>
        </div>
    </div>
</div>
@endsection
