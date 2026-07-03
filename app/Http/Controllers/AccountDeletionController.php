<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountDeletionController extends Controller
{
    public function deactivate(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isCompany()) {
            $company = $user->company;

            if ($company && $company->bikes()->where('status', '!=', 'inactive')->exists()) {
                return back()->with('error', 'Deactivate all bike listings before deleting your account.');
            }
        }

        $user->update(['account_status' => 'deactivated']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deactivated.');
    }
}
