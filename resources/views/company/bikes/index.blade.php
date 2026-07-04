@extends('layouts.company')
@section('title', 'My Bikes')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Bikes</h1>
        <p class="mt-1 text-gray-600">Manage your bike inventory.</p>
    </div>
    <x-button href="{{ route('company.bikes.create') }}">Add New Bike</x-button>
</div>

@if (session('success'))
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
@endif

@if ($bikes->isEmpty())
    <div class="mt-6">
        <x-card>
            <p class="text-gray-500 text-center py-8">No bikes listed yet. <a href="{{ route('company.bikes.create') }}" class="text-gray-900 font-medium hover:underline">Add your first bike</a>.</p>
        </x-card>
    </div>
@else
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($bikes as $bike)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if ($bike->images->where('is_primary', true)->first())
                    <img src="{{ asset('storage/' . $bike->images->where('is_primary', true)->first()->image_path) }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">No image</div>
                @endif
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $bike->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $bike->brand }} {{ $bike->model }} ({{ $bike->year }})</p>
                        </div>
                        <x-badge :variant="$bike->status === 'active' ? 'green' : ($bike->status === 'maintenance' ? 'yellow' : 'gray')">{{ $bike->status }}</x-badge>
                    </div>
                    <p class="text-lg font-bold text-gray-900">NPR {{ number_format($bike->daily_price, 2) }}<span class="text-sm font-normal text-gray-500">/day</span></p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">{{ $bike->fuel_type }} &middot; {{ $bike->transmission }}</span>
                        <span @class(['font-medium', 'text-green-600' => $bike->is_available, 'text-red-600' => !$bike->is_available])>{{ $bike->is_available ? 'Available' : 'Unavailable' }}</span>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('company.bikes.edit', $bike) }}" class="flex-1 text-center px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Edit</a>
                        <form method="POST" action="{{ route('company.bikes.toggle-availability', $bike) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                {{ $bike->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
