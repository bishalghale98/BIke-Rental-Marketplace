@extends('layouts.company')
@section('title', 'Company Profile')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900">Company Profile</h1>
    <p class="mt-1 text-gray-600">Manage your business information.</p>

    @if (session('success'))
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Business Information</h3>">
            <div class="space-y-4">
                <x-input label="Company Name" name="company_name" required value="{{ old('company_name', $company->company_name) }}" />
                <x-input label="Owner Name" name="owner_name" required value="{{ old('owner_name', $company->owner_name) }}" />
                <x-input label="Address" name="address" value="{{ old('address', $company->address) }}" />
                <x-input label="Contact Number" name="contact_number" value="{{ old('contact_number', $company->contact_number) }}" />
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="4" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">{{ old('description', $company->description) }}</textarea>
                </div>
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Branding</h3>">
            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Logo</label>
                    @if ($company->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="h-20 w-auto rounded-lg border border-gray-200 object-contain">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                    @if ($company->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $company->cover_image) }}" alt="Cover Image" class="h-32 w-full max-w-md rounded-lg border border-gray-200 object-cover">
                        </div>
                    @endif
                    <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>
        </x-card>

        <div class="flex justify-end">
            <x-button type="submit">Save Changes</x-button>
        </div>
    </form>
</div>
@endsection
