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
        <x-card class="mt-6" header="<div class='flex items-center justify-between'><h3 class='text-lg font-medium text-gray-900'>Request Payout</h3><a href='{{ route('company.bank-details.index') }}' class='text-sm font-medium text-primary-600 hover:text-primary-700'>Manage Bank Accounts</a></div>">
            <form method="POST" action="{{ route('company.payouts.request') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount (NPR)</label>
                        <input type="number" name="amount" step="0.01" min="1" max="{{ $balance }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <p class="mt-1 text-xs text-gray-400">Max: NPR {{ number_format($balance, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bank Account</label>
                        @if ($bankDetails->count())
                            <select name="bank_detail_id" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                <option value="">Select a bank account</option>
                                @foreach ($bankDetails as $detail)
                                    <option value="{{ $detail->id }}" {{ $detail->is_default ? 'selected' : '' }}>
                                        {{ $detail->bank_name }} - {{ $detail->account_name }} ({{ $detail->account_number }}){{ $detail->is_default ? ' [Default]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="mt-1 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
                                No bank accounts saved.
                                <a href="{{ route('company.bank-details.index') }}" class="font-medium underline">Add one now</a>.
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                    </div>
                    <button type="submit" {{ $bankDetails->count() ? '' : 'disabled' }}
                        class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors {{ $bankDetails->count() ? '' : 'opacity-50 cursor-not-allowed' }}">
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
                        <div class="flex items-center gap-2">
                            <span @class([
                                'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                                'bg-yellow-50 text-yellow-700' => $payout->status === 'pending',
                                'bg-blue-50 text-blue-700' => $payout->status === 'processing',
                                'bg-green-50 text-green-700' => $payout->status === 'paid',
                                'bg-red-50 text-red-700' => $payout->status === 'failed',
                            ])>
                                {{ ucfirst($payout->status) }}
                            </span>
                            @if (in_array($payout->status, ['paid', 'processing', 'failed']))
                                <a href="{{ route('company.payouts.invoice', $payout) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View</a>
                            @endif
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
