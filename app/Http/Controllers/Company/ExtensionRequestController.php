<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\ExtensionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExtensionRequestController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $requests = ExtensionRequest::whereIn('booking_id', function ($q) use ($bikeIds) {
            $q->select('id')->from('bookings')->whereIn('bike_id', $bikeIds);
        })->with('booking.bike', 'customer.user')
            ->latest()
            ->paginate(15);

        return view('company.extensions.index', compact('requests'));
    }

    public function approve(ExtensionRequest $extensionRequest): RedirectResponse
    {
        $company = Auth::user()->company;
        abort_if($extensionRequest->booking->bike->company_id !== $company->id, 403);
        abort_if($extensionRequest->status !== 'pending', 400);

        $extensionRequest->update([
            'status' => 'approved',
            'handled_at' => now(),
        ]);

        $extensionRequest->booking->update([
            'extended_until' => $extensionRequest->requested_end_date,
        ]);

        return back()->with('success', 'Extension approved.');
    }

    public function deny(ExtensionRequest $extensionRequest): RedirectResponse
    {
        $company = Auth::user()->company;
        abort_if($extensionRequest->booking->bike->company_id !== $company->id, 403);
        abort_if($extensionRequest->status !== 'pending', 400);

        $extensionRequest->update([
            'status' => 'denied',
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Extension denied.');
    }
}
