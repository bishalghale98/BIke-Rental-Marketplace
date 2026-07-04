@php
    $user = auth()->user();
@endphp

<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-xl border-t border-zinc-200 dark:border-zinc-800 safe-area-bottom">
    <div class="flex items-center justify-around h-16 px-2">
        <a href="/" @class(['flex flex-col items-center gap-0.5 px-3 py-1.5', 'text-primary-600 dark:text-primary-400' => request()->routeIs('home'), 'text-zinc-500 dark:text-zinc-400' => !request()->routeIs('home')])>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="{{ route('bikes.index') }}" @class(['flex flex-col items-center gap-0.5 px-3 py-1.5', 'text-primary-600 dark:text-primary-400' => request()->routeIs('bikes.*'), 'text-zinc-500 dark:text-zinc-400' => !request()->routeIs('bikes.*')])>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="text-[10px] font-medium">Search</span>
        </a>
        @auth
            <a href="{{ $user->bookingsRoute() }}" @class(['flex flex-col items-center gap-0.5 px-3 py-1.5', 'text-primary-600 dark:text-primary-400' => request()->routeIs('customer.bookings*') || request()->routeIs('company.bookings*'), 'text-zinc-500 dark:text-zinc-400' => !request()->routeIs('customer.bookings*') && !request()->routeIs('company.bookings*')])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-[10px] font-medium">Bookings</span>
            </a>
            <a href="{{ route('customer.wishlist') }}" @class(['flex flex-col items-center gap-0.5 px-3 py-1.5', 'text-primary-600 dark:text-primary-400' => request()->routeIs('customer.wishlist'), 'text-zinc-500 dark:text-zinc-400' => !request()->routeIs('customer.wishlist')])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span class="text-[10px] font-medium">Wishlist</span>
            </a>
        @endauth
        <div x-data="{ open: false }">
            <button @click="open = !open" :aria-expanded="open" aria-haspopup="dialog" class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-zinc-500 dark:text-zinc-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                <span class="text-[10px] font-medium">More</span>
            </button>
            <div x-show="open" x-cloak class="fixed inset-x-0 top-0 bottom-16 z-40 bg-black/40" x-transition.opacity @click="open = false"></div>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="fixed inset-x-0 bottom-16 z-50">
                <div class="w-full max-w-lg mx-auto rounded-t-2xl bg-white dark:bg-zinc-900 shadow-2xl">
                    <div class="flex justify-center pt-3 pb-2">
                        <div class="h-1 w-10 rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
                    </div>
                    <div class="max-h-[50vh] overflow-y-auto px-3 pb-3">
                        @auth
                            <a href="{{ $user->dashboardRoute() }}" @click="open = false" class="mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </span>
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </span>
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" @click="open = false" class="mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                </span>
                                Login
                            </a>
                            <a href="{{ route('register') }}" @click="open = false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                </span>
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
