@extends('layouts.public')
@section('title', $bike->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('bikes.index') }}" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Bikes</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-zinc-900 dark:text-zinc-100 font-medium truncate">{{ $bike->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        {{-- Left: Gallery + Info --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Gallery --}}
            <div x-data="{ active: 0 }" class="space-y-3">
                <div class="aspect-[16/9] rounded-2xl overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                    @if ($bike->images->count() > 0)
                        <img :src="'{{ asset('storage/') }}' + '/' + $el.closest('[x-data]').querySelectorAll('[data-img]')[active].dataset.img"
                            x-ref="mainImage" src="{{ asset('storage/' . ($bike->images->first()->image_path ?? '')) }}"
                            alt="{{ $bike->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                @if ($bike->images->count() > 1)
                    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                        @foreach ($bike->images as $index => $image)
                            <button @click="active = {{ $index }}"
                                :class="{ 'ring-2 ring-primary-500': active === {{ $index }} }"
                                class="shrink-0 w-20 h-16 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 hover:border-primary-400 dark:hover:border-primary-600 transition-colors cursor-pointer">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Description</h2>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $bike->description ?? 'No description provided.' }}</p>
            </div>

            {{-- Features --}}
            @if ($bike->features)
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Features</h2>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($bike->features as $feature)
                            <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Rental Rules --}}
            @if ($bike->rental_rules)
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Rental Rules</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach ($bike->rental_rules as $rule)
                            <li class="flex items-start gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-zinc-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $rule }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Reviews --}}
            @if ($bike->reviews->count() > 0)
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Reviews ({{ $bike->reviews->count() }})</h2>
                    <div class="space-y-3">
                        @foreach ($bike->reviews as $review)
                            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-zinc-900 dark:text-zinc-100 text-sm">{{ $review->customer?->user?->name ?? 'Anonymous' }}</span>
                                            <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-0.5 mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-warning-500 fill-current' : 'text-zinc-200 dark:text-zinc-700 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @if ($review->review)
                                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $review->review }}</p>
                                @endif
                                @if ($review->reply)
                                    <div class="mt-3 pl-4 border-l-2 border-primary-200 dark:border-primary-800">
                                        <p class="text-xs font-medium text-primary-600 dark:text-primary-400">Company reply</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $review->reply }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Sticky Booking Card --}}
        <div class="lg:col-span-2">
            <div class="lg:sticky lg:top-24 space-y-6">
                {{-- Pricing Card --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
                    <div class="space-y-4">
                        <div>
                            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">{{ $bike->name }}</h1>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $bike->brand }} {{ $bike->model }} ({{ $bike->year }})</p>
                        </div>

                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-zinc-900 dark:text-zinc-50">NPR {{ number_format($bike->daily_price, 0) }}</span>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">/ day</span>
                        </div>

                        @if ($bike->hourly_price || $bike->weekly_price)
                            <div class="flex flex-wrap gap-3 text-sm">
                                @if ($bike->hourly_price)
                                    <span class="px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">NPR {{ number_format($bike->hourly_price, 0) }}/hr</span>
                                @endif
                                @if ($bike->daily_price)
                                    <span class="px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">NPR {{ number_format($bike->daily_price, 0) }}/day</span>
                                @endif
                                @if ($bike->weekly_price)
                                    <span class="px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">NPR {{ number_format($bike->weekly_price, 0) }}/wk</span>
                                @endif
                            </div>
                        @endif

                        {{-- Specs Grid --}}
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-sm">
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400 text-xs">Category</span>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $bike->category?->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400 text-xs">Fuel</span>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $bike->fuel_type ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400 text-xs">Transmission</span>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $bike->transmission ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400 text-xs">Engine</span>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $bike->engine_capacity ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400 text-xs">Mileage</span>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $bike->mileage ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400 text-xs">Color</span>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $bike->color ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 text-sm font-medium text-success-600 dark:text-success-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Available for booking
                        </div>

                        {{-- CTA --}}
                        @if (auth()->check() && auth()->user()->hasRole('Customer'))
                            <a href="{{ route('customer.bookings.create', $bike) }}" class="block w-full text-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors shadow-sm">Book Now</a>
                        @elseif (!auth()->check())
                            <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors shadow-sm">Log in to Book</a>
                        @endif
                    </div>
                </div>

                {{-- Company Card --}}
                @if ($bike->company)
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
                        <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Rental Company</h3>
                        <div class="flex items-center gap-3">
                            @if ($bike->company->logo)
                                <img src="{{ asset('storage/' . $bike->company->logo) }}" alt="{{ $bike->company->company_name }}" class="w-12 h-12 rounded-xl object-cover">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-lg font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($bike->company->company_name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <h4 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $bike->company->company_name }}</h4>
                                @if ($bike->company->address)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $bike->company->address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Related Bikes --}}
    @if ($relatedBikes->count() > 0)
        <div class="mt-16">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 mb-6">Similar Bikes</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedBikes as $related)
                    <a href="{{ route('bikes.show', $related) }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-xl hover:-translate-y-0.5 transition-all duration-150 cursor-pointer">
                        <div class="aspect-[4/3] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                            @if ($related->primaryImage)
                                <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-300 dark:text-zinc-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3.5 space-y-1.5">
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 text-sm line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $related->name }}</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $related->brand }} {{ $related->model }}</p>
                            <p class="text-sm font-bold text-zinc-900 dark:text-zinc-50">NPR {{ number_format($related->daily_price, 0) }}<span class="text-xs font-normal text-zinc-500 dark:text-zinc-400">/day</span></p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
