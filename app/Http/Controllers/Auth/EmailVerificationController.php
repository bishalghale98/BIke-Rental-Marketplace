<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request, string $id, string $hash): RedirectResponse
    {
        if (!hash_equals((string) $id, (string) $request->user()->getKey())) {
            return back()->with('error', 'Invalid verification link.');
        }

        if (!hash_equals((string) $hash, sha1($request->user()->getEmailForVerification()))) {
            return back()->with('error', 'Invalid verification link.');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('success', 'Email already verified.');
        }

        $request->user()->markEmailAsVerified();

        return redirect()->route('home')->with('success', 'Email verified successfully.');
    }

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('success', 'Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent.');
    }
}
