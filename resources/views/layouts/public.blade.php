<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Home')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div x-data="{ mobileMenuOpen: false }">
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="/" class="text-xl font-bold text-gray-900">
                            {{ config('app.name') }}
                        </a>
                        <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                            <a href="{{ route('bikes.index') }}" class="hover:text-gray-900">Browse Bikes</a>
                        </nav>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800">Register</a>
                        @endauth
                    </div>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-200">
                <div class="px-4 py-3 space-y-2">
                    <a href="{{ route('bikes.index') }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Browse Bikes</a>
                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Log in</a>
                        <a href="{{ route('register') }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Register</a>
                    @endauth
                </div>
            </div>
        </header>
        <main>
            @yield('content')
        </main>
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
