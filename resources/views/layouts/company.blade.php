<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Company Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div x-data="{ sidebarOpen: false }">
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <a href="/" class="text-lg font-bold text-gray-900">{{ config('app.name') }}</a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->company?->name ?? auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                    </form>
                </div>
            </div>
        </header>
        <div class="lg:flex">
            <aside x-show="sidebarOpen" @click.away="sidebarOpen = false" x-cloak class="fixed inset-0 z-50 lg:relative lg:flex lg:w-64 lg:shrink-0">
                <div class="absolute inset-0 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>
                <nav class="relative w-64 bg-white border-r border-gray-200 h-full overflow-y-auto p-4 space-y-1">
                    <div class="flex items-center justify-between mb-6 lg:hidden">
                        <span class="font-semibold">Menu</span>
                        <button @click="sidebarOpen = false" class="p-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <x-sidebar-link href="{{ route('company.dashboard') }}" :active="request()->routeIs('company.dashboard')">Dashboard</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.bikes.index') }}" :active="request()->routeIs('company.bikes.*')">My Bikes</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.bookings') }}" :active="request()->routeIs('company.bookings')">Bookings</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.calendar') }}" :active="request()->routeIs('company.calendar')">Calendar</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.reviews') }}" :active="request()->routeIs('company.reviews')">Reviews</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.analytics') }}" :active="request()->routeIs('company.analytics')">Analytics</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.reports') }}" :active="request()->routeIs('company.reports')">Reports</x-sidebar-link>
                    <x-sidebar-link href="{{ route('company.profile') }}" :active="request()->routeIs('company.profile')">Company Profile</x-sidebar-link>
                    <hr class="my-4">
                    <a href="{{ route('bikes.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100">View Marketplace</a>
                </nav>
            </aside>
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
