<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterCompanyController extends Controller
{
    public function create(): View
    {
        return view('auth.register-company');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        $user->assignRole(RoleEnum::Company);

        CompanyProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'owner_name' => $request->owner_name,
            'contact_number' => $request->phone,
            'verification_status' => 'pending',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('company.dashboard', absolute: false));
    }
}
