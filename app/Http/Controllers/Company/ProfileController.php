<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $company = Auth::user()->company;
        return view('company.profile', compact('company'));
    }

    public function update(Request $request): RedirectResponse
    {
        $company = Auth::user()->company;

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'opening_hours' => ['nullable', 'json'],
            'social_links' => ['nullable', 'json'],
        ]);

        $data = $request->except(['logo', 'cover_image']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('companies/covers', 'public');
        }

        $company->update($data);

        return back()->with('success', 'Company profile updated successfully.');
    }
}
