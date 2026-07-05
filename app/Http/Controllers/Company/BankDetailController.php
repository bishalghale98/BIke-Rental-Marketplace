<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BankDetailController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;
        $bankDetails = $company->bankDetails()->latest()->get();

        return view('company.bank-details.index', compact('company', 'bankDetails'));
    }

    public function store(Request $request): RedirectResponse
    {
        $company = Auth::user()->company;

        $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'branch' => ['nullable', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
            'qr_code' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $data = $request->only(['bank_name', 'account_name', 'account_number', 'branch']);

        if ($request->boolean('is_default')) {
            $company->bankDetails()->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('qr-codes', 'public');
        }

        $company->bankDetails()->create($data);

        return redirect()->route('company.bank-details.index')
            ->with('success', 'Bank account added successfully.');
    }

    public function update(Request $request, BankDetail $bankDetail): RedirectResponse
    {
        $company = Auth::user()->company;

        abort_if($bankDetail->company_id !== $company->id, 403);

        $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'branch' => ['nullable', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
            'qr_code' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $data = $request->only(['bank_name', 'account_name', 'account_number', 'branch']);

        if ($request->boolean('is_default')) {
            $company->bankDetails()->where('id', '!=', $bankDetail->id)->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        if ($request->hasFile('qr_code')) {
            if ($bankDetail->qr_code) {
                Storage::disk('public')->delete($bankDetail->qr_code);
            }
            $data['qr_code'] = $request->file('qr_code')->store('qr-codes', 'public');
        }

        $bankDetail->update($data);

        return redirect()->route('company.bank-details.index')
            ->with('success', 'Bank account updated successfully.');
    }

    public function destroy(BankDetail $bankDetail): RedirectResponse
    {
        $company = Auth::user()->company;

        abort_if($bankDetail->company_id !== $company->id, 403);

        if ($bankDetail->qr_code) {
            Storage::disk('public')->delete($bankDetail->qr_code);
        }

        $bankDetail->delete();

        return redirect()->route('company.bank-details.index')
            ->with('success', 'Bank account removed.');
    }
}
