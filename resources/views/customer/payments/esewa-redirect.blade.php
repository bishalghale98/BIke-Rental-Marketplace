@extends('layouts.customer')
@section('title', 'Redirecting to eSewa...')

@section('content')
<div class="max-w-lg mx-auto text-center py-12">
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8">
        <div class="animate-spin w-12 h-12 border-4 border-zinc-200 border-t-zinc-900 rounded-full mx-auto"></div>
        <h1 class="mt-4 text-xl font-semibold text-gray-900">Redirecting to eSewa...</h1>
        <p class="mt-2 text-gray-600">Please wait while we redirect you to eSewa's secure payment page.</p>

        <form id="esewa-form" method="POST" action="{{ $result['payment_url'] }}" class="hidden">
            <input type="hidden" name="amount" value="{{ $result['total_amount'] }}">
            <input type="hidden" name="tax_amount" value="0">
            <input type="hidden" name="total_amount" value="{{ $result['total_amount'] }}">
            <input type="hidden" name="transaction_uuid" value="{{ $result['transaction_uuid'] }}">
            <input type="hidden" name="product_code" value="{{ $result['product_code'] }}">
            <input type="hidden" name="product_service_charge" value="0">
            <input type="hidden" name="product_delivery_charge" value="0">
            <input type="hidden" name="success_url" value="{{ route('customer.payment.callback', ['gateway' => 'esewa']) }}">
            <input type="hidden" name="failure_url" value="{{ route('customer.payment.failure', $booking) }}">
            <input type="hidden" name="signed_field_names" value="{{ $result['signed_field_names'] }}">
            <input type="hidden" name="signature" value="{{ $result['signature'] }}">
        </form>

        <script>document.getElementById('esewa-form').submit();</script>
    </div>
</div>
@endsection
