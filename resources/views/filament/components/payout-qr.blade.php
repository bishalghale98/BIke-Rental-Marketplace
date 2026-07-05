@php
    $payout = $getRecord();
    $company = $payout?->company;
    $bankDetail = $company?->bankDetails()->default()->first() ?? $company?->bankDetails()->first();
    $qrPath = $bankDetail?->qr_code ? asset('storage/' . $bankDetail->qr_code) : null;
@endphp

<div class="flex items-center gap-4">
    @if ($qrPath)
        <img src="{{ $qrPath }}" alt="QR Code" class="w-24 h-24 object-cover rounded border">
        <div class="text-sm text-gray-500">
            <p>{{ $bankDetail->bank_name }} - {{ $bankDetail->account_name }}</p>
            <p class="text-xs">Default QR code from company's bank account</p>
        </div>
    @else
        <p class="text-sm text-gray-400">No QR code associated with this payout.</p>
    @endif
</div>
