<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Home')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-zinc-900 bg-white dark:bg-zinc-950 dark:text-zinc-100">
    <div x-data="{ mobileMenu: false, theme: '{{ session('theme', 'light') }}' }" x-init="$watch('theme', val => { document.documentElement.className = val === 'dark' ? 'dark' : ''; fetch('{{ route('theme.toggle') }}?theme=' + val, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }); })">
        {{-- Navbar --}}
        <x-header-navbar />

        {{-- Main Content --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-50">{{ config('app.name') }}</h3>
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Find verified bikes for rent. Ride with confidence.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Explore</h4>
                        <ul class="mt-3 space-y-2">
                            <li><a href="{{ route('bikes.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">Browse Bikes</a></li>
                            <li><a href="{{ route('register.company') }}" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">List Your Bikes</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Support</h4>
                        <ul class="mt-3 space-y-2">
                            <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">Help Center</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">Safety</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">Company</h4>
                        <ul class="mt-3 space-y-2">
                            <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">About Us</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">Blog</a></li>
                            <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 transition-colors">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                        <a href="#" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Privacy</a>
                        <a href="#" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Terms</a>
                    </div>
                </div>
            </div>
        </footer>

        <x-mobile-bottom-nav />
    </div>
    @stack('scripts')
</body>
</html>
