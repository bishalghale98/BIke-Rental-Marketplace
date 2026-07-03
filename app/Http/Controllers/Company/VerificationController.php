<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function show(): View
    {
        $company = Auth::user()->company()->with('verification')->firstOrFail();
        return view('company.verification', compact('company'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $company = Auth::user()->company;

        $request->validate([
            'registration_number' => ['required', 'string', 'max:100'],
            'pan_number' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'contact_number' => ['required', 'string', 'max:20'],
            'registration_certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'pan_certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'owner_citizenship' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'owner_photo' => ['required', 'image', 'max:2048'],
        ]);

        $company->update($request->only('registration_number', 'pan_number', 'address', 'contact_number'));

        $verification = $company->verification()->firstOrNew();

        $verification->fill($request->except(['registration_certificate', 'pan_certificate', 'owner_citizenship', 'owner_photo']));

        foreach ([
            'registration_certificate' => 'registration_certificate',
            'pan_certificate' => 'pan_certificate',
            'owner_citizenship' => 'owner_citizenship',
            'owner_photo' => 'owner_photo',
        ] as $input => $column) {
            if ($request->hasFile($input)) {
                $verification->{$column} = $request->file($input)->store('verifications/company', 'public');
            }
        }

        if (!$verification->exists) {
            $verification->company_id = $company->id;
        }

        $verification->status = 'pending';
        $verification->save();

        $company->update(['verification_status' => 'pending']);

        return back()->with('success', 'Company verification documents submitted successfully.');
    }
}
