@extends('layouts.public')
@section('title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight">
            Rent a Bike, Anywhere.
        </h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
            Browse bikes from trusted companies, compare prices, and book your ride — by the hour, day, or week.
        </p>
        <div class="mt-8 flex items-center justify-center gap-4">
            <x-button href="{{ route('bikes.index') }}" size="lg">Browse Bikes</x-button>
            <x-button href="{{ route('register') }}" variant="secondary" size="lg">Get Started</x-button>
        </div>
    </div>

    <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
        <x-card>
            <div class="text-center">
                <div class="text-3xl mb-4">🏍️</div>
                <h3 class="text-lg font-semibold text-gray-900">Wide Selection</h3>
                <p class="mt-2 text-sm text-gray-600">Choose from hundreds of bikes — scooters, cruisers, sport bikes, and more.</p>
            </div>
        </x-card>
        <x-card>
            <div class="text-center">
                <div class="text-3xl mb-4">💰</div>
                <h3 class="text-lg font-semibold text-gray-900">Flexible Pricing</h3>
                <p class="mt-2 text-sm text-gray-600">Hourly, daily, or weekly rates. Only pay for what you need.</p>
            </div>
        </x-card>
        <x-card>
            <div class="text-center">
                <div class="text-3xl mb-4">✅</div>
                <h3 class="text-lg font-semibold text-gray-900">Verified Companies</h3>
                <p class="mt-2 text-sm text-gray-600">All companies are verified. Rent with confidence and peace of mind.</p>
            </div>
        </x-card>
    </div>
</div>
@endsection
