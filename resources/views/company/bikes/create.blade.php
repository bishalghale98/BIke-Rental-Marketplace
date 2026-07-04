@extends('layouts.company')
@section('title', 'Add Bike')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900">Add New Bike</h1>
    <p class="mt-1 text-gray-600">List a new bike for rent.</p>

    @if (session('error'))
        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('company.bikes.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Basic Information</h3>">
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Bike Name" name="name" required value="{{ old('name') }}" />
                <x-input label="Brand" name="brand" required value="{{ old('brand') }}" />
                <x-input label="Model" name="model" required value="{{ old('model') }}" />
                <x-input label="Year" name="year" type="number" value="{{ old('year') }}" />
                <x-input label="Engine Capacity" name="engine_capacity" placeholder="e.g. 150cc" value="{{ old('engine_capacity') }}" />
                <x-select label="Fuel Type" name="fuel_type" :options="['petrol' => 'Petrol', 'diesel' => 'Diesel', 'electric' => 'Electric', 'other' => 'Other']" value="{{ old('fuel_type') }}" />
                <x-select label="Transmission" name="transmission" :options="['manual' => 'Manual', 'automatic' => 'Automatic']" value="{{ old('transmission') }}" />
                <x-input label="Mileage" name="mileage" placeholder="e.g. 45 km/l" value="{{ old('mileage') }}" />
                <x-input label="Color" name="color" value="{{ old('color') }}" />
                <x-select label="Category" name="category_id" :options="$categories->pluck('name', 'id')->prepend('Select Category', '')->toArray()" value="{{ old('category_id') }}" />
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Inventory Details</h3>">
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Bike Number" name="bike_number" value="{{ old('bike_number') }}" />
                <x-input label="Registration Number" name="registration_number" value="{{ old('registration_number') }}" />
                <x-input label="VIN" name="vin" value="{{ old('vin') }}" />
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Pricing</h3>">
            <div class="grid grid-cols-3 gap-4">
                <x-input label="Hourly Price (NPR)" name="hourly_price" type="number" step="0.01" value="{{ old('hourly_price') }}" />
                <x-input label="Daily Price (NPR)" name="daily_price" type="number" step="0.01" required value="{{ old('daily_price') }}" />
                <x-input label="Weekly Price (NPR)" name="weekly_price" type="number" step="0.01" value="{{ old('weekly_price') }}" />
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Description & Details</h3>">
            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">{{ old('description') }}</textarea>
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Features (one per line)</label>
                    <textarea name="features" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900" placeholder="Helmet included&#10;Phone charger&#10;Side bags">{{ old('features') }}</textarea>
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Rental Rules (one per line)</label>
                    <textarea name="rental_rules" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900" placeholder="Minimum age: 21&#10;Valid driving license required&#10;Security deposit: NPR 5000">{{ old('rental_rules') }}</textarea>
                </div>
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Images</h3>">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Bike Images (max 10)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-500">The first image will be used as the primary photo.</p>
            </div>
        </x-card>

        <div class="flex justify-end gap-4">
            <x-button href="{{ route('company.bikes.index') }}" variant="secondary">Cancel</x-button>
            <x-button type="submit">List Bike</x-button>
        </div>
    </form>
</div>
@endsection
