@extends('layouts.admin')
@section('title', 'Manage Payouts')

@section('content')
<div class="max-w-6xl">
    <h1 class="text-2xl font-bold text-gray-900">Payouts</h1>
    <p class="mt-1 text-gray-600">Manage rental business payout requests.</p>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-card>
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">NPR {{ number_format($stats['pending'], 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Processing</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">NPR {{ number_format($stats['processing'], 2) }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Paid This Month</p>
            <p class="text-2xl font-bold text-green-600 mt-1">NPR {{ number_format($stats['paid_this_month'], 2) }}</p>
        </x-card>
    </div>

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>All Payout Requests</h3>">
        @if ($payouts->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200">
                            <th class="text-left py-3 px-2 font-medium text-gray-500">ID</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Company</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Amount</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Status</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-500">Requested</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payouts as $payout)
                            <tr class="border-b border-zinc-100 last:border-0">
                                <td class="py-3 px-2 font-mono text-xs">#{{ $payout->id }}</td>
                                <td class="py-3 px-2 font-medium">{{ $payout->company->company_name }}</td>
                                <td class="py-3 px-2 font-medium">NPR {{ number_format($payout->amount, 2) }}</td>
                                <td class="py-3 px-2">
                                    <span @class([
                                        'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                                        'bg-yellow-50 text-yellow-700' => $payout->status === 'pending',
                                        'bg-blue-50 text-blue-700' => $payout->status === 'processing',
                                        'bg-green-50 text-green-700' => $payout->status === 'paid',
                                        'bg-red-50 text-red-700' => $payout->status === 'failed',
                                    ])>{{ ucfirst($payout->status) }}</span>
                                </td>
                                <td class="py-3 px-2 text-gray-600">{{ $payout->created_at->format('M d, Y') }}</td>
                                <td class="py-3 px-2 text-right">
                                    <a href="{{ route('admin.payouts.show', $payout) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $payouts->links() }}</div>
        @else
            <p class="text-gray-500 text-center py-8">No payout requests yet.</p>
        @endif
    </x-card>
</div>
@endsection
