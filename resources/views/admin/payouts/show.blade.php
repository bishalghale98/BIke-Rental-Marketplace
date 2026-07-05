@extends('layouts.admin')
@section('title', 'Payout #' . $payout->id)

@section('content')
<div class="max-w-3xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.payouts.index') }}" class="hover:text-gray-900">Payouts</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">#{{ $payout->id }}</span>
    </nav>

    <div class="space-y-6">
        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Payout Details</h3>">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Company</span><span class="font-medium">{{ $payout->company->company_name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Amount</span><span class="font-bold text-lg">NPR {{ number_format($payout->amount, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status</span>
                    <span @class([
                        'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                        'bg-yellow-50 text-yellow-700' => $payout->status === 'pending',
                        'bg-blue-50 text-blue-700' => $payout->status === 'processing',
                        'bg-green-50 text-green-700' => $payout->status === 'paid',
                        'bg-red-50 text-red-700' => $payout->status === 'failed',
                    ])>{{ ucfirst($payout->status) }}</span>
                </div>
                <div class="flex justify-between"><span class="text-gray-500">Requested</span><span>{{ $payout->created_at->format('M d, Y g:i A') }}</span></div>
                @if ($payout->paid_at)
                    <div class="flex justify-between"><span class="text-gray-500">Paid At</span><span>{{ $payout->paid_at->format('M d, Y g:i A') }}</span></div>
                @endif
                @if ($payout->processor)
                    <div class="flex justify-between"><span class="text-gray-500">Processed By</span><span>{{ $payout->processor->name }}</span></div>
                @endif
            </div>
        </x-card>

        <x-card header="<h3 class='text-lg font-medium text-gray-900'>Bank Details</h3>">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Bank</span><span class="font-medium">{{ $payout->bank_details['bank_name'] ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Account Name</span><span class="font-medium">{{ $payout->bank_details['bank_account_name'] ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Account Number</span><span class="font-medium">{{ $payout->bank_details['bank_account_number'] ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Branch</span><span>{{ $payout->bank_details['bank_branch'] ?? 'N/A' }}</span></div>
            </div>
        </x-card>

        @if ($payout->notes)
            <x-card header="<h3 class='text-lg font-medium text-gray-900'>Notes</h3>">
                <p class="text-sm text-gray-600">{{ $payout->notes }}</p>
            </x-card>
        @endif

        @if ($payout->status === 'pending')
            <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                    onclick="return confirm('Approve this payout?')">
                    Approve & Mark as Processing
                </button>
            </form>
        @endif

        @if ($payout->status === 'processing')
            <form method="POST" action="{{ route('admin.payouts.mark-paid', $payout) }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors"
                    onclick="return confirm('Confirm that the bank transfer has been completed?')">
                    Mark as Paid
                </button>
            </form>
        @endif

        @if (in_array($payout->status, ['pending', 'processing']))
            <form method="POST" action="{{ route('admin.payouts.mark-failed', $payout) }}" class="inline ml-2">
                @csrf
                <input type="text" name="reason" placeholder="Reason for failure" required class="inline-block rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors"
                    onclick="return confirm('Mark this payout as failed?')">
                    Mark Failed
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
