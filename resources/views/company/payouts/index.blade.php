@extends('layouts.company')
@section('title', 'Payouts')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payouts</h1>
            <p class="mt-1 text-gray-600">Manage your earnings and payout requests.</p>
        </div>
        <a href="{{ route('company.payouts.history') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
            View Ledger →
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-card>
            <p class="text-sm text-gray-500">Available Balance</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($balance, 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Pending Payouts</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($payouts->whereIn('status', ['pending', 'processing'])->sum('amount'), 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Total Paid Out</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($payouts->where('status', 'paid')->sum('amount'), 2) }}</p>
        </x-card>
    </div>

    @if ($balance > 0)
        <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Request Payout</h3>">
            <form method="POST" action="{{ route('company.payouts.request') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount (NPR)</label>
                        <input type="number" name="amount" step="0.01" min="1" max="{{ $balance }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <p class="mt-1 text-xs text-gray-400">Max: NPR {{ number_format($balance, 2) }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" name="bank_name" required class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Name</label>
                            <input type="text" name="bank_account_name" required class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Number</label>
                            <input type="text" name="bank_account_number" required class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Branch (optional)</label>
                            <input type="text" name="bank_branch" class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors">
                        Submit Payout Request
                    </button>
                </div>
            </form>
        </x-card>
    @endif

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Payout History</h3>">
        @if ($payouts->count())
            <div class="space-y-3">
                @foreach ($payouts as $payout)
                    <div class="flex items-center justify-between py-3 border-b border-zinc-100 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-900">NPR {{ number_format($payout->amount, 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $payout->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span @class([
                                'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                                'bg-yellow-50 text-yellow-700' => $payout->status === 'pending',
                                'bg-blue-50 text-blue-700' => $payout->status === 'processing',
                                'bg-green-50 text-green-700' => $payout->status === 'paid',
                                'bg-red-50 text-red-700' => $payout->status === 'failed',
                            ])>
                                {{ ucfirst($payout->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $payouts->links() }}</div>
        @else
            <p class="text-gray-500 text-center py-8">No payout requests yet.</p>
        @endif
    </x-card>
</div>
@endsection
