@extends('layouts.public')
@section('title', 'Verify Email')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md text-center">
        <div class="mx-auto w-16 h-16 rounded-2xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Verify your email</h1>
        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">We've sent a verification link to your email address. Click the link to activate your account.</p>

        @if (session('success'))
            <div class="mt-4 p-3 bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-800 rounded-xl text-sm text-success-700 dark:text-success-400">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
            @csrf
            <x-button type="submit" class="w-full">Resend verification email</x-button>
        </form>

        <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline">Sign out</a>
        </p>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
    </div>
</div>
@endsection
