@extends('layouts.company')
@section('title', 'Edit Bike')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Bike</h1>
            <p class="mt-1 text-gray-600">{{ $bike->name }}</p>
        </div>
        <x-badge :variant="$bike->status === 'active' ? 'green' : ($bike->status === 'maintenance' ? 'yellow' : 'gray')">{{ $bike->status }}</x-badge>
    </div>

    <form method="POST" action="{{ route('company.bikes.update', $bike) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Basic Information</h3>">
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Bike Name" name="name" required value="{{ old('name', $bike->name) }}" />
                <x-input label="Brand" name="brand" required value="{{ old('brand', $bike->brand) }}" />
                <x-input label="Model" name="model" required value="{{ old('model', $bike->model) }}" />
                <x-input label="Year" name="year" type="number" value="{{ old('year', $bike->year) }}" />
                <x-input label="Engine Capacity" name="engine_capacity" value="{{ old('engine_capacity', $bike->engine_capacity) }}" />
                <x-select label="Fuel Type" name="fuel_type" :options="['petrol' => 'Petrol', 'diesel' => 'Diesel', 'electric' => 'Electric', 'other' => 'Other']" value="{{ old('fuel_type', $bike->fuel_type) }}" />
                <x-select label="Transmission" name="transmission" :options="['manual' => 'Manual', 'automatic' => 'Automatic']" value="{{ old('transmission', $bike->transmission) }}" />
                <x-input label="Mileage" name="mileage" value="{{ old('mileage', $bike->mileage) }}" />
                <x-input label="Color" name="color" value="{{ old('color', $bike->color) }}" />
                <x-select label="Category" name="category_id" :options="$categories->pluck('name', 'id')->prepend('Select Category', '')->toArray()" value="{{ old('category_id', $bike->category_id) }}" />
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Inventory Details</h3>">
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Bike Number" name="bike_number" value="{{ old('bike_number', $bike->bike_number) }}" />
                <x-input label="Registration Number" name="registration_number" value="{{ old('registration_number', $bike->registration_number) }}" />
                <x-input label="VIN" name="vin" value="{{ old('vin', $bike->vin) }}" />
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Pricing</h3>">
            <div class="grid grid-cols-3 gap-4">
                <x-input label="Hourly Price (NPR)" name="hourly_price" type="number" step="0.01" value="{{ old('hourly_price', $bike->hourly_price) }}" />
                <x-input label="Daily Price (NPR)" name="daily_price" type="number" step="0.01" required value="{{ old('daily_price', $bike->daily_price) }}" />
                <x-input label="Weekly Price (NPR)" name="weekly_price" type="number" step="0.01" value="{{ old('weekly_price', $bike->weekly_price) }}" />
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Status</h3>">
            <x-select label="Bike Status" name="status" :options="['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance']" value="{{ old('status', $bike->status) }}" />
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Description & Details</h3>">
            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">{{ old('description', $bike->description) }}</textarea>
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Features (one per line)</label>
                    <textarea name="features" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">{{ old('features', is_array($bike->features) ? implode("\n", $bike->features) : '') }}</textarea>
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Rental Rules (one per line)</label>
                    <textarea name="rental_rules" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">{{ old('rental_rules', is_array($bike->rental_rules) ? implode("\n", $bike->rental_rules) : '') }}</textarea>
                </div>
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Images</h3>">
            @if ($bike->images->count() > 0)
                <div class="grid grid-cols-4 gap-4 mb-4">
                    @foreach ($bike->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-24 object-cover rounded-lg">
                            @if ($image->is_primary)
                                <span class="absolute top-1 left-1 text-xs bg-gray-900 text-white px-1.5 py-0.5 rounded">Primary</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Add More Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>
        </x-card>

        <div class="flex justify-end gap-4">
            <x-button href="{{ route('company.bikes.index') }}" variant="secondary">Cancel</x-button>
            <x-button type="submit">Save Changes</x-button>
        </div>
    </form>
</div>
@endsection
