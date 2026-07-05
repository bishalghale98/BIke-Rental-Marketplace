@extends('layouts.company')
@section('title', 'Payout Invoice')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payout Invoice</h1>
            <p class="mt-1 text-gray-600">Invoice #{{ $payout->id }}</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors print:hidden">
            Download / Print PDF
        </button>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 p-8">
        <div class="flex justify-between items-start pb-6 border-b border-zinc-200">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ config('app.name') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Payout Invoice</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Date: {{ $payout->paid_at?->format('M d, Y') ?? $payout->created_at->format('M d, Y') }}</p>
                <p class="text-sm text-gray-500">Invoice #: {{ $payout->id }}</p>
            </div>
        </div>

        <div class="py-6 border-b border-zinc-200">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">From</h3>
                    <p class="text-sm text-gray-700 mt-2">{{ config('app.name') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">To</h3>
                    <p class="text-sm text-gray-700 mt-2">{{ $company->company_name }}</p>
                    @if ($company->address)
                        <p class="text-sm text-gray-500">{{ $company->address }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="py-6 border-b border-zinc-200">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Bank Account Details</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Bank:</span>
                    <span class="text-gray-900 ml-2">{{ $payout->bank_details['bank_name'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Account Name:</span>
                    <span class="text-gray-900 ml-2">{{ $payout->bank_details['account_name'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Account Number:</span>
                    <span class="text-gray-900 ml-2">{{ $payout->bank_details['account_number'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Branch:</span>
                    <span class="text-gray-900 ml-2">{{ $payout->bank_details['branch'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="py-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200">
                        <th class="text-left py-2 font-semibold text-gray-900">Description</th>
                        <th class="text-right py-2 font-semibold text-gray-900">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-zinc-100">
                        <td class="py-3 text-gray-700">Payout - {{ $payout->status === 'paid' ? 'Paid' : ucfirst($payout->status) }}</td>
                        <td class="py-3 text-right text-gray-900 font-medium">NPR {{ number_format($payout->amount, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="py-3 text-sm font-semibold text-gray-900">Total</td>
                        <td class="py-3 text-right text-lg font-bold text-gray-900">NPR {{ number_format($payout->amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="pt-6 border-t border-zinc-200 text-center text-sm text-gray-500">
            <p>Thank you for partnering with {{ config('app.name') }}</p>
            <p class="mt-1">Invoice generated on {{ now()->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="mt-4 text-center print:hidden">
        <a href="{{ route('company.payouts.index') }}" class="text-sm text-primary-600 hover:text-primary-700">← Back to Payouts</a>
    </div>
</div>

<style>
@media print {
    body { background: white; }
    header, aside, nav, .print\:hidden { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
    .bg-white { border: none !important; box-shadow: none !important; }
}
</style>
@endsection