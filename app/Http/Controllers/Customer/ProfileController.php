<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user()->load('customerProfile');
        return view('customer.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'permanent_address' => ['nullable', 'string', 'max:500'],
            'current_address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($request->only('name', 'phone'));

        $profile = $user->customerProfile()->firstOrNew();
        $profile->fill($request->only('date_of_birth', 'gender', 'permanent_address', 'current_address'));

        if ($request->hasFile('profile_photo')) {
            $profile->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        $profile->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
