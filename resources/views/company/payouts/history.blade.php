@extends('layouts.company')
@section('title', 'Wallet Ledger')

@section('content')
<div class="max-w-4xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('company.payouts.index') }}" class="hover:text-gray-900">Payouts</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Ledger</span>
    </nav>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Wallet Transactions</h1>
            <p class="mt-1 text-gray-600">Complete financial ledger for your business.</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">Current Balance</p>
            <p class="text-xl font-bold text-gray-900">NPR {{ number_format($company->balance, 2) }}</p>
        </div>
    </div>

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Transaction History</h3>">
        @if ($transactions->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200">
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Date</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Type</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Description</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-500">Amount</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-500">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $txn)
                            <tr class="border-b border-zinc-100 last:border-0">
                                <td class="py-3 px-2 text-gray-600 whitespace-nowrap">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-2">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $txn->direction === 'credit' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $txn->direction === 'credit' ? 'Credit' : 'Debit' }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-gray-600">{{ $txn->description ?? str_replace('_', ' ', $txn->type) }}</td>
                                <td class="py-3 px-2 text-right font-medium {{ $txn->direction === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $txn->direction === 'credit' ? '+' : '-' }} NPR {{ number_format($txn->amount, 2) }}
                                </td>
                                <td class="py-3 px-2 text-right text-gray-900 font-medium">NPR {{ number_format($txn->balance_after, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $transactions->links() }}</div>
        @else
            <p class="text-gray-500 text-center py-8">No transactions yet.</p>
        @endif
    </x-card>
</div>
@endsection
