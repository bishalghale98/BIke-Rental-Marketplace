@extends('layouts.admin')
@section('title', 'Financial Dashboard')

@section('content')
<div class="max-w-6xl">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Financial Dashboard</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm px-3 py-1.5">
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm px-3 py-1.5">
            <button type="submit" class="px-3 py-1.5 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800">Filter</button>
        </form>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-card>
            <p class="text-sm text-gray-500">Platform Revenue (Commission)</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($totalCommission, 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Total Booking Value</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($totalRevenue, 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Pending Payouts</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">NPR {{ number_format($pendingPayouts, 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Refunds ({{ $startDate->format('M') }})</p>
            <p class="text-2xl font-bold text-red-600 mt-1">NPR {{ number_format($totalRefunds, 2) }}</p>
        </x-card>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-card>
            <p class="text-sm text-gray-500">Total Paid Out</p>
            <p class="text-2xl font-bold text-green-600 mt-1">NPR {{ number_format($completedPayouts, 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Failed Payments ({{ $startDate->format('M') }})</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $failedPayments }}</p>
        </x-card>
    </div>

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Commission by Company</h3>">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200">
                        <th class="text-left py-3 px-2 font-medium text-gray-500">Company</th>
                        <th class="text-center py-3 px-2 font-medium text-gray-500">Commission %</th>
                        <th class="text-center py-3 px-2 font-medium text-gray-500">Bookings</th>
                        <th class="text-right py-3 px-2 font-medium text-gray-500">Commission Earned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companyCommissions as $company)
                        <tr class="border-b border-zinc-100 last:border-0">
                            <td class="py-3 px-2 font-medium">{{ $company->company_name }}</td>
                            <td class="py-3 px-2 text-center">{{ $company->commission_percent }}%</td>
                            <td class="py-3 px-2 text-center">{{ $company->booking_count }}</td>
                            <td class="py-3 px-2 text-right font-medium">NPR {{ number_format($company->commission_earned, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Recent Wallet Transactions</h3>">
        @if ($recentTransactions->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200">
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Company</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Type</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Direction</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-500">Amount</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-500">Balance</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentTransactions as $txn)
                            <tr class="border-b border-zinc-100 last:border-0">
                                <td class="py-3 px-2">{{ $txn->company->company_name }}</td>
                                <td class="py-3 px-2 text-xs">{{ str_replace('_', ' ', $txn->type) }}</td>
                                <td class="py-3 px-2">
                                    <span class="text-xs font-medium {{ $txn->direction === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($txn->direction) }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-right font-medium">NPR {{ number_format($txn->amount, 2) }}</td>
                                <td class="py-3 px-2 text-right">NPR {{ number_format($txn->balance_after, 2) }}</td>
                                <td class="py-3 px-2 text-right text-gray-500">{{ $txn->created_at->format('M d, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No transactions yet.</p>
        @endif
    </x-card>
</div>
@endsection
