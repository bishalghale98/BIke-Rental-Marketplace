<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function show(): View
    {
        $user = Auth::user();
        $verification = $user->customerProfile?->verification;
        return view('customer.verification', compact('user', 'verification'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'citizenship_number' => ['required', 'string', 'max:50'],
            'permanent_address' => ['required', 'string', 'max:500'],
            'current_address' => ['required', 'string', 'max:500'],
            'license_number' => ['required', 'string', 'max:50'],
            'license_expiry_date' => ['required', 'date', 'after:today'],
            'license_front' => ['required', 'image', 'max:2048'],
            'license_back' => ['required', 'image', 'max:2048'],
            'citizenship_front' => ['required', 'image', 'max:2048'],
            'citizenship_back' => ['required', 'image', 'max:2048'],
            'selfie' => ['required', 'image', 'max:2048'],
        ]);

        $profile = $user->customerProfile()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'full_name' => $request->full_name,
        ]);

        $profile->update($request->only('full_name', 'date_of_birth', 'gender', 'citizenship_number', 'permanent_address', 'current_address'));

        $verification = $profile->verification()->firstOrNew();

        $verification->fill($request->only('license_number', 'license_expiry_date'));

        foreach ([
            'license_front' => 'license_front_image',
            'license_back' => 'license_back_image',
            'citizenship_front' => 'citizenship_front',
            'citizenship_back' => 'citizenship_back',
            'selfie' => 'selfie',
        ] as $input => $column) {
            if ($request->hasFile($input)) {
                $verification->{$column} = $request->file($input)->store('verifications/customer', 'public');
            }
        }

        $verification->status = 'pending';
        $verification->save();

        $profile->update(['verification_status' => 'pending']);

        return back()->with('success', 'Verification documents submitted successfully. Awaiting review.');
    }
}
