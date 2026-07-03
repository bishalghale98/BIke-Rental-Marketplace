@extends('layouts.customer')
@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
    <p class="mt-1 text-gray-600">Manage your personal information.</p>

    @if (session('success'))
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Personal Information</h3>">
            <div class="space-y-4">
                <x-input label="Full Name" name="name" required value="{{ old('name', $user->name) }}" />
                <x-input label="Email" name="email" type="email" value="{{ $user->email }}" disabled />
                <x-input label="Phone" name="phone" value="{{ old('phone', $user->phone) }}" />

                <x-input label="Date of Birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $user->customerProfile?->date_of_birth?->format('Y-m-d')) }}" />

                <x-select label="Gender" name="gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" value="{{ old('gender', $user->customerProfile?->gender) }}" />

                <x-input label="Permanent Address" name="permanent_address" value="{{ old('permanent_address', $user->customerProfile?->permanent_address) }}" />
                <x-input label="Current Address" name="current_address" value="{{ old('current_address', $user->customerProfile?->current_address) }}" />

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>
        </x-card>

        <div class="flex justify-end">
            <x-button type="submit">Save Changes</x-button>
        </div>
    </form>
</div>
@endsection
