@php
    $payout = $getRecord();
    $proofPath = $payout?->payment_proof ? asset('storage/' . $payout->payment_proof) : null;
@endphp

<div>
    @if ($proofPath)
        <a href="{{ $proofPath }}" target="_blank">
            <img src="{{ $proofPath }}" alt="Payment Screenshot" class="max-w-sm rounded border object-cover">
        </a>
        <p class="mt-1 text-xs text-gray-500">Click to view full size</p>
    @else
        <p class="text-sm text-gray-400">No payment screenshot uploaded.</p>
    @endif
</div>
