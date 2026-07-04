<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;
        $reviews = Review::where('company_id', $company->id)
            ->with('bike.images', 'customer.user', 'booking')
            ->latest()
            ->paginate(15);

        return view('company.reviews.index', compact('reviews'));
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $company = Auth::user()->company;

        abort_if($review->company_id !== $company->id, 403);

        $request->validate([
            'reply' => ['required', 'string', 'max:5000'],
        ]);

        $review->update([
            'reply' => $request->reply,
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Reply submitted successfully.');
    }
}
