@extends('layouts.company')
@section('title', 'Company Verification')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900">Business Verification</h1>
    <p class="mt-1 text-gray-600">Verify your business to publish bikes on the marketplace.</p>

    @if (session('success'))
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if ($company->verification_status === 'pending')
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
            Your verification documents are under review.
        </div>
    @elseif ($company->verification_status === 'verified')
        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            Your business has been verified. You can now publish bikes.
        </div>
    @elseif ($company->verification_status === 'rejected')
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            Your verification was rejected: {{ $company->verification?->rejected_reason ?? 'No reason provided.' }}
        </div>
    @endif

    @if ($company->verification_status !== 'verified')
        <form method="POST" action="{{ route('company.verification.submit') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Business Details</h3>">
                <div class="space-y-4">
                    <x-input label="Registration Number" name="registration_number" required value="{{ old('registration_number', $company->registration_number) }}" />
                    <x-input label="PAN Number" name="pan_number" required value="{{ old('pan_number', $company->pan_number) }}" />
                    <x-input label="Business Address" name="address" required value="{{ old('address', $company->address) }}" />
                    <x-input label="Contact Number" name="contact_number" required value="{{ old('contact_number', $company->contact_number) }}" />
                </div>
            </x-card>

            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Documents</h3>">
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Company Registration Certificate</label>
                        <input type="file" name="registration_certificate" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">PAN Certificate</label>
                        <input type="file" name="pan_certificate" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Owner Citizenship</label>
                            <input type="file" name="owner_citizenship" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Owner Photo</label>
                            <input type="file" name="owner_photo" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
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
