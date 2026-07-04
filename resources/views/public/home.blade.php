@extends('layouts.public')
@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-gradient-to-b from-primary-50/50 to-white dark:from-zinc-950 dark:to-zinc-950">
    <div class="absolute inset-0 bg-grid opacity-50 dark:opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-50 leading-tight">
                Rent Your Perfect Ride
            </h1>
            <p class="mt-4 sm:mt-6 text-lg sm:text-xl text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto">
                Find verified bikes near you. Book by the hour, day, or week — with confidence.
            </p>
        </div>

        {{-- Search Bar --}}
        <div class="mt-8 sm:mt-10 max-w-2xl mx-auto">
            <form action="{{ route('bikes.index') }}" method="GET" class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700 p-2 sm:p-3">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" placeholder="Search by name, brand, or company..." class="w-full rounded-xl border-0 text-sm pl-10 pr-4 py-3 bg-transparent focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        Search
                    </button>
                </div>
                <div class="flex flex-wrap gap-2 mt-3 px-1 pb-1">
                    <select name="category" class="text-xs rounded-lg border border-zinc-200 dark:border-zinc-700 px-3 py-1.5 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="fuel_type" class="text-xs rounded-lg border border-zinc-200 dark:border-zinc-700 px-3 py-1.5 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20">
                        <option value="">Fuel Type</option>
                        <option value="petrol">Petrol</option>
                        <option value="electric">Electric</option>
                    </select>
                    <select name="transmission" class="text-xs rounded-lg border border-zinc-200 dark:border-zinc-700 px-3 py-1.5 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20">
                        <option value="">Transmission</option>
                        <option value="manual">Manual</option>
                        <option value="automatic">Automatic</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Stats --}}
        <div class="mt-12 sm:mt-16 grid grid-cols-2 sm:grid-cols-3 gap-6 max-w-lg mx-auto">
            <div class="text-center">
                <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-zinc-50">{{ $totalBikes }}+</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Bikes Available</p>
            </div>
            <div class="text-center">
                <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-zinc-50">{{ $totalCompanies }}+</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Verified Companies</p>
            </div>
            <div class="text-center col-span-2 sm:col-span-1">
                <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-zinc-50">{{ $totalBookings }}+</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Bookings Made</p>
            </div>
        </div>
    </div>
</section>

{{-- Featured Categories --}}
@if ($categories->count() > 0)
<section class="py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Browse by Category</h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">Find the perfect bike type for your journey</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($categories as $category)
                <a href="{{ route('bikes.index', ['category' => $category->slug]) }}" class="group relative bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 text-center hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150 cursor-pointer">
                    <div class="w-14 h-14 mx-auto rounded-xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $category->name }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $category->bikes_count }} bikes</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Featured Bikes --}}
@if ($featured->count() > 0)
<section class="py-16 sm:py-20 bg-zinc-50/50 dark:bg-zinc-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Featured Bikes</h2>
                <p class="mt-2 text-zinc-500 dark:text-zinc-400">Top picks for your next adventure</p>
            </div>
            <a href="{{ route('bikes.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">
                View all
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($featured as $bike)
                <a href="{{ route('bikes.show', $bike) }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-150 cursor-pointer">
                    <div class="relative aspect-[4/3] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if ($bike->primaryImage)
                            <img src="{{ asset('storage/' . $bike->primaryImage->image_path) }}" alt="{{ $bike->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        @if ($bike->company)
                            <span class="absolute top-2 left-2 px-2 py-1 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm text-[10px] font-medium text-zinc-600 dark:text-zinc-400 rounded-lg">{{ $bike->company->company_name }}</span>
                        @endif
                    </div>
                    <div class="p-4 space-y-2">
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $bike->name }}</h3>
                        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <span>{{ $bike->fuel_type }}</span>
                            <span class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                            <span>{{ $bike->transmission }}</span>
                            @if ($bike->engine_capacity)
                                <span class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                                <span>{{ $bike->engine_capacity }}</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <p class="text-lg font-bold text-zinc-900 dark:text-zinc-50">NPR {{ number_format($bike->daily_price, 0) }}<span class="text-xs font-normal text-zinc-500 dark:text-zinc-400">/day</span></p>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 dark:text-primary-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                Book now
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('bikes.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">
                View all bikes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Why Choose Us --}}
<section class="py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Why Choose {{ config('app.name') }}</h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">We make bike rental simple, safe, and seamless</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Verified & Trusted</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Every company is verified. Every bike is listed with real photos and accurate specs.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Flexible Rentals</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Hourly, daily, or weekly — choose the rental period that fits your schedule and budget.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-warning-50 dark:bg-warning-500/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                </div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Easy Booking</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Book in minutes with our simple step-by-step process. No hidden fees or surprises.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Secure Payments</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Pay securely online with transparent pricing and automated refunds for cancellations.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Customer Support</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Our team is here to help. Reach out anytime for assistance with bookings or issues.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-pink-50 dark:bg-pink-500/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                </div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Wide Selection</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">From scooters to sport bikes, find the perfect ride for every trip and occasion.</p>
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="py-16 sm:py-20 bg-zinc-50/50 dark:bg-zinc-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">How It Works</h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">Get on the road in four simple steps</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center relative">
                <div class="w-16 h-16 mx-auto rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg">1</div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Search</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Browse our collection of verified bikes and find the one that suits your needs.</p>
            </div>
            <div class="text-center relative">
                <div class="w-16 h-16 mx-auto rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg">2</div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Choose</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Select your dates, compare prices, and pick the perfect rental plan.</p>
            </div>
            <div class="text-center relative">
                <div class="w-16 h-16 mx-auto rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg">3</div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Book</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Confirm your booking in seconds. No paperwork, no hassle.</p>
            </div>
            <div class="text-center relative">
                <div class="w-16 h-16 mx-auto rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg">4</div>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-zinc-100">Ride</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Pick up your bike and hit the road. Enjoy the ride!</p>
            </div>
        </div>
    </div>
</section>

{{-- Popular Companies --}}
@if ($companies->count() > 0)
<section class="py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Popular Companies</h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">Trusted rental providers in your area</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($companies as $company)
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 flex items-center gap-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150 cursor-pointer">
                    @if ($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->company_name }}" class="w-14 h-14 rounded-xl object-cover">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-lg font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($company->company_name, 0, 2)) }}</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 truncate">{{ $company->company_name }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $company->bikes_count }} bikes</p>
                    </div>
                    <svg class="w-5 h-5 text-zinc-300 dark:text-zinc-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
<section class="py-16 sm:py-20 bg-zinc-50/50 dark:bg-zinc-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">What Our Users Say</h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">Real experiences from real riders</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6">
                <div class="flex items-center gap-1 text-warning-500">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current text-zinc-200 dark:text-zinc-700" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">"Amazing experience! The bike was in perfect condition and the booking process was incredibly smooth. Will definitely rent again."</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-500 dark:text-zinc-400">RK</div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Rajesh K.</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Happy Customer</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6">
                <div class="flex items-center gap-1 text-warning-500">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">"I run a small tour company and use this platform regularly. The company dashboard is fantastic for managing bookings and tracking revenue."</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-500 dark:text-zinc-400">SP</div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Sagar P.</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Company Partner</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6">
                <div class="flex items-center gap-1 text-warning-500">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">"The verification process was thorough and the platform feels very secure. I love the variety of bikes available. Highly recommended!"</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-500 dark:text-zinc-400">AL</div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Anita L.</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Verified Rider</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-16 sm:py-20" x-data="{ open: null }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Frequently Asked Questions</h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">Everything you need to know about renting</p>
        </div>
        <div class="space-y-3">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <button @click="open = open === 1 ? null : 1" class="w-full flex items-center justify-between p-5 text-left">
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">How do I rent a bike?</span>
                    <svg class="w-5 h-5 text-zinc-400 transition-transform duration-150" :class="{ 'rotate-180': open === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 1" x-collapse class="px-5 pb-5">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Simply browse available bikes, select your desired dates, and book. You'll need to create a customer account and complete verification to get started.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <button @click="open = open === 2 ? null : 2" class="w-full flex items-center justify-between p-5 text-left">
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">What payment methods are accepted?</span>
                    <svg class="w-5 h-5 text-zinc-400 transition-transform duration-150" :class="{ 'rotate-180': open === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 2" x-collapse class="px-5 pb-5">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">We accept payments through our secure payment gateway. Your payment is processed at the time of booking confirmation.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <button @click="open = open === 3 ? null : 3" class="w-full flex items-center justify-between p-5 text-left">
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Can I cancel my booking?</span>
                    <svg class="w-5 h-5 text-zinc-400 transition-transform duration-150" :class="{ 'rotate-180': open === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 3" x-collapse class="px-5 pb-5">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Yes, you can cancel bookings within your account. Refunds follow our policy: full refund if cancelled more than 24 hours before start, 50% refund within 24 hours.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <button @click="open = open === 4 ? null : 4" class="w-full flex items-center justify-between p-5 text-left">
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">How do I become a rental company?</span>
                    <svg class="w-5 h-5 text-zinc-400 transition-transform duration-150" :class="{ 'rotate-180': open === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 4" x-collapse class="px-5 pb-5">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Register as a company, complete your business profile and verification. Once verified, you can start listing your bikes and receiving bookings.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Banner --}}
<section class="pb-16 sm:pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 to-primary-800 px-8 py-12 sm:py-16 text-center">
            <div class="absolute inset-0 bg-grid opacity-10"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-4xl font-bold text-white">Ready to hit the road?</h2>
                <p class="mt-3 text-primary-100 max-w-xl mx-auto">Join thousands of riders who trust {{ config('app.name') }} for their bike rental needs.</p>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('bikes.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-700 font-semibold rounded-xl hover:bg-zinc-50 transition-colors shadow-lg">
                        Browse Bikes
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('register.company') }}" class="inline-flex items-center px-6 py-3 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors">
                        List Your Bikes
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
