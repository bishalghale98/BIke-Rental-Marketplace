@extends('layouts.customer')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Welcome --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Welcome, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-zinc-500 dark:text-zinc-400">Manage your rentals and account.</p>
    </div>

    @if ($showOnboarding)
        {{-- No profile yet — prompt to create one --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-8 sm:p-12 text-center max-w-2xl mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h2 class="mt-6 text-xl font-semibold text-zinc-900 dark:text-zinc-100">Complete Your Profile</h2>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 max-w-md mx-auto">Set up your profile to start renting bikes. You'll need to provide your details and complete verification before you can book.</p>
            <a href="{{ route('customer.profile') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 text-white font-medium text-sm hover:bg-primary-700 active:scale-[0.97] transition-all duration-150">
                Set Up Profile
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @elseif (!$isVerified)
        {{-- Profile exists but not verified --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-8 sm:p-12 text-center max-w-2xl mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-warning-50 dark:bg-warning-500/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h2 class="mt-6 text-xl font-semibold text-zinc-900 dark:text-zinc-100">Verification {{ ucfirst($verificationStatus) }}</h2>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 max-w-md mx-auto">
                @if ($verificationStatus === 'pending')
                    Your profile is under review. We'll notify you once it's approved. You'll be able to book bikes once verified.
                @else
                    Please submit your verification documents to start renting bikes.
                @endif
            </p>
            <a href="{{ route('customer.verification') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 text-white font-medium text-sm hover:bg-primary-700 active:scale-[0.97] transition-all duration-150">
                {{ $verificationStatus === 'pending' ? 'Check Status' : 'Submit Verification' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @else
        {{-- Verified — full dashboard --}}
        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <x-stat-card label="Upcoming Bookings" :value="$upcomingCount" color="primary"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' />
            <x-stat-card label="Active Rentals" :value="$activeCount" color="success"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>' />
            <x-stat-card label="Completed" :value="$completedCount" color="warning"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
            <x-stat-card label="Verification" value="Verified" color="success"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>' />
        </div>

        {{-- Booking Lists --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Upcoming & Active --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Upcoming & Active Bookings</h2>
                    <a href="{{ route('customer.bookings.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">View all</a>
                </div>
                @if ($upcomingBookings->isEmpty())
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">No upcoming bookings.</p>
                        <a href="{{ route('bikes.index') }}" class="mt-3 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Browse bikes &rarr;</a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($upcomingBookings as $booking)
                            <a href="{{ route('customer.bookings.show', $booking) }}" class="block bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-150">
                                <div class="flex items-center gap-4">
                                    @if ($booking->bike->images->where('is_primary', true)->first())
                                        <img src="{{ asset('storage/' . $booking->bike->images->where('is_primary', true)->first()->image_path) }}" alt="" class="w-14 h-14 rounded-xl object-cover">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $booking->bike->name }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $booking->start_date->format('M d, g:i A') }} &mdash; {{ $booking->end_date->format('M d, g:i A') }}</p>
                                    </div>
                                    <x-badge :variant="$booking->status->value === 'ongoing' ? 'blue' : 'yellow'">{{ ucfirst($booking->status->value) }}</x-badge>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recently Completed --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Recently Completed</h2>
                    <a href="{{ route('customer.bookings.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">View all</a>
                </div>
                @if ($recentCompleted->isEmpty())
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">No completed rentals yet.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($recentCompleted as $booking)
                            <a href="{{ route('customer.bookings.show', $booking) }}" class="block bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-150">
                                <div class="flex items-center gap-4">
                                    @if ($booking->bike->images->where('is_primary', true)->first())
                                        <img src="{{ asset('storage/' . $booking->bike->images->where('is_primary', true)->first()->image_path) }}" alt="" class="w-14 h-14 rounded-xl object-cover">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $booking->bike->name }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $booking->start_date->format('M d, Y') }} &mdash; {{ $booking->end_date->format('M d, Y') }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">NPR {{ number_format($booking->total_amount, 0) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('bikes.index') }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-150">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Browse Bikes</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Find your next ride</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('customer.profile') }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-150">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 group-hover:text-success-600 dark:group-hover:text-success-400 transition-colors">My Profile</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Update personal info</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('customer.verification') }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-150">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-warning-50 dark:bg-warning-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 group-hover:text-warning-600 dark:group-hover:text-warning-400 transition-colors">Verification</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Verified</p>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div>
@endsection
