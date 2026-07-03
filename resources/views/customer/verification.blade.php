@extends('layouts.customer')
@section('title', 'Verification')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900">Identity Verification</h1>
    <p class="mt-1 text-gray-600">You need to verify your identity before booking a bike.</p>

    @if (session('success'))
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if ($verification && $verification->status === 'pending')
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
            Your verification documents are under review. This usually takes 1-2 business days.
        </div>
    @elseif ($verification && $verification->status === 'verified')
        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            Your identity has been verified. You can now book bikes.
        </div>
    @elseif ($verification && $verification->status === 'rejected')
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            Your verification was rejected: {{ $verification->rejected_reason ?? 'No reason provided.' }}
            Please resubmit with correct documents.
        </div>
    @endif

    @if (!$verification || $verification->status !== 'verified')
        <form method="POST" action="{{ route('customer.verification.submit') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Personal Information</h3>">
                <div class="space-y-4">
                    <x-input label="Full Name (as on citizenship)" name="full_name" required value="{{ old('full_name', $user->customerProfile?->full_name ?? $user->name) }}" />
                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="Date of Birth" name="date_of_birth" type="date" required value="{{ old('date_of_birth', $user->customerProfile?->date_of_birth?->format('Y-m-d')) }}" />
                        <x-select label="Gender" name="gender" required :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" value="{{ old('gender', $user->customerProfile?->gender) }}" />
                    </div>
                    <x-input label="Citizenship Number" name="citizenship_number" required value="{{ old('citizenship_number') }}" />
                    <x-input label="Permanent Address" name="permanent_address" required value="{{ old('permanent_address', $user->customerProfile?->permanent_address) }}" />
                    <x-input label="Current Address" name="current_address" required value="{{ old('current_address', $user->customerProfile?->current_address) }}" />
                </div>
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Driving License</h3>">
                <div class="space-y-4">
                    <x-input label="License Number" name="license_number" required value="{{ old('license_number', $verification?->license_number) }}" />
                    <x-input label="License Expiry Date" name="license_expiry_date" type="date" required value="{{ old('license_expiry_date', $verification?->license_expiry_date?->format('Y-m-d')) }}" />
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">License Front Image</label>
                            <input type="file" name="license_front" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">License Back Image</label>
                            <input type="file" name="license_back" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Documents</h3>">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Citizenship Front</label>
                            <input type="file" name="citizenship_front" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Citizenship Back</label>
                            <input type="file" name="citizenship_back" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Selfie Photo</label>
                        <input type="file" name="selfie" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end">
                <x-button type="submit">Submit Verification</x-button>
            </div>
        </form>
    @endif
</div>
@endsection
