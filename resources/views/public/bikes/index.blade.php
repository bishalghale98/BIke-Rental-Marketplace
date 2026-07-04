@extends('layouts.public')
@section('title', 'Browse Bikes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Browse Bikes</h1>
            <p class="mt-1 text-zinc-500 dark:text-zinc-400">{{ $bikes->total() }} bikes available</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('bikes.index') }}" class="mt-6 sm:mt-8 space-y-4">
        {{-- Search + Sort --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" placeholder="Search by name, brand, or company..." value="{{ request('search') }}"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm pl-10 pr-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:text-zinc-100 dark:placeholder:text-zinc-500">
            </div>
            <select name="sort" onchange="this.form.submit()" class="rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm px-4 py-2.5 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
                <option value="name" @selected(request('sort') === 'name')>Name</option>
            </select>
        </div>

        {{-- Filter Chips --}}
        <div class="flex flex-wrap gap-2">
            <select name="brand" onchange="this.form.submit()" class="text-sm rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3.5 py-2 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                <option value="">All Brands</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>
                @endforeach
            </select>
            <select name="category" onchange="this.form.submit()" class="text-sm rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3.5 py-2 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="fuel_type" onchange="this.form.submit()" class="text-sm rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3.5 py-2 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                <option value="">All Fuel</option>
                <option value="petrol" @selected(request('fuel_type') === 'petrol')>Petrol</option>
                <option value="diesel" @selected(request('fuel_type') === 'diesel')>Diesel</option>
                <option value="electric" @selected(request('fuel_type') === 'electric')>Electric</option>
            </select>
            <select name="transmission" onchange="this.form.submit()" class="text-sm rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3.5 py-2 text-zinc-600 dark:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                <option value="">All Transmissions</option>
                <option value="manual" @selected(request('transmission') === 'manual')>Manual</option>
                <option value="automatic" @selected(request('transmission') === 'automatic')>Automatic</option>
            </select>
            <input type="number" name="min_price" placeholder="Min price" value="{{ request('min_price') }}"
                class="w-24 text-sm rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-zinc-600 dark:text-zinc-400 placeholder:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            <input type="number" name="max_price" placeholder="Max price" value="{{ request('max_price') }}"
                class="w-24 text-sm rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-zinc-600 dark:text-zinc-400 placeholder:text-zinc-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            @if (request()->anyFilled(['search', 'brand', 'category', 'fuel_type', 'transmission', 'min_price', 'max_price', 'sort']))
                <a href="{{ route('bikes.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">Clear filters</a>
            @endif
        </div>
    </form>

    {{-- Bike Grid --}}
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
        @forelse ($bikes as $bike)
            <a href="{{ route('bikes.show', $bike) }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-150 cursor-pointer">
                <div class="relative aspect-[4/3] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                    @if ($bike->primaryImage)
                        <img src="{{ asset('storage/' . $bike->primaryImage->image_path) }}" alt="{{ $bike->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <button onclick="event.preventDefault(); event.stopPropagation();" class="absolute top-3 right-3 p-2 rounded-full bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm hover:bg-white dark:hover:bg-zinc-900 transition-colors">
                        <svg class="w-4 h-4 text-zinc-400 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>
                <div class="p-4 space-y-2.5">
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $bike->name }}</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $bike->brand }} {{ $bike->model }} ({{ $bike->year }})</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                        <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800">{{ $bike->fuel_type }}</span>
                        <span>{{ $bike->transmission }}</span>
                        @if ($bike->engine_capacity)
                            <span class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                            <span>{{ $bike->engine_capacity }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <p class="text-lg font-bold text-zinc-900 dark:text-zinc-50">NPR {{ number_format($bike->daily_price, 0) }}<span class="text-xs font-normal text-zinc-500 dark:text-zinc-400">/day</span></p>
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 dark:text-primary-400 opacity-0 group-hover:opacity-100 transition-opacity">
                            Book
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">No bikes found</h3>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('bikes.index') }}" class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 border border-primary-200 dark:border-primary-800 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors">Clear all filters</a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($bikes->hasPages())
        <div class="mt-10">
            {{ $bikes->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
