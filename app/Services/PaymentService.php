<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private WalletService $walletService,
        private CommissionService $commissionService,
    ) {}

    public function initiateDeposit(Booking $booking, string $gateway): array
    {
        $depositAmount = $booking->deposit_amount;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'type' => 'deposit',
            'gateway' => $gateway,
            'status' => 'pending',
            'amount' => $depositAmount,
            'attempt_number' => $this->nextAttemptNumber($booking, 'deposit'),
        ]);

        return match ($gateway) {
            'khalti' => $this->initiateKhalti($payment, $booking),
            'esewa' => $this->initiateEsewa($payment, $booking),
            default => throw new \InvalidArgumentException("Unsupported gateway: {$gateway}"),
        };
    }

    public function initiateRemaining(Booking $booking, string $gateway): array
    {
        $remainingAmount = $booking->remaining_amount;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'type' => 'remaining',
            'gateway' => $gateway,
            'status' => 'pending',
            'amount' => $remainingAmount,
            'attempt_number' => $this->nextAttemptNumber($booking, 'remaining'),
        ]);

        return match ($gateway) {
            'khalti' => $this->initiateKhalti($payment, $booking),
            'esewa' => $this->initiateEsewa($payment, $booking),
            default => throw new \InvalidArgumentException("Unsupported gateway: {$gateway}"),
        };
    }

    public function verify(string $gateway, array $data): Payment
    {
        return match ($gateway) {
            'khalti' => $this->verifyKhalti($data),
            'esewa' => $this->verifyEsewa($data),
            default => throw new \InvalidArgumentException("Unsupported gateway: {$gateway}"),
        };
    }

    public function refund(Payment $payment): Payment
    {
        if ($payment->status !== 'completed') {
            throw new \RuntimeException('Only completed payments can be refunded.');
        }

        $response = match ($payment->gateway) {
            'khalti' => $this->refundKhalti($payment),
            'esewa' => $this->refundEsewa($payment),
            default => throw new \InvalidArgumentException("Unsupported gateway: {$payment->gateway}"),
        };

        $payment->update([
            'status' => 'refunded',
            'gateway_response' => $response,
        ]);

        $refundPayment = Payment::create([
            'booking_id' => $payment->booking_id,
            'type' => 'refund',
            'gateway' => $payment->gateway,
            'gateway_txn_id' => $response['refund_txn_id'] ?? null,
            'status' => 'completed',
            'amount' => $payment->amount,
            'paid_at' => now(),
        ]);

        return $refundPayment;
    }

    private function nextAttemptNumber(Booking $booking, string $type): int
    {
        return Payment::where('booking_id', $booking->id)
            ->where('type', $type)
            ->max('attempt_number') + 1;
    }

    private function initiateKhalti(Payment $payment, Booking $booking): array
    {
        $amountInPaisa = (int) round($payment->amount * 100);

        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
            'Content-Type' => 'application/json',
        ])->post('https://dev.khalti.com/api/v2/epayment/initiate/', [
            'return_url' => route('customer.payment.callback', ['gateway' => 'khalti']),
            'website_url' => config('app.url'),
            'amount' => $amountInPaisa,
            'purchase_order_id' => (string) $payment->id,
            'purchase_order_name' => "Booking {$booking->booking_number} Deposit",
            'customer_info' => [
                'name' => $booking->customer?->user?->name ?? 'Customer',
                'email' => $booking->customer?->user?->email ?? '',
                'phone' => $booking->customer?->user?->phone ?? '',
            ],
        ]);

        $body = $response->json();

        if (!$response->successful()) {
            $payment->update(['status' => 'failed', 'gateway_response' => $body]);
            throw new \RuntimeException($body['detail'] ?? 'Khalti initiation failed.');
        }

        $payment->update([
            'gateway_txn_id' => $body['pidx'],
            'gateway_response' => $body,
        ]);

        return [
            'payment_id' => $payment->id,
            'payment_url' => $body['payment_url'],
            'pidx' => $body['pidx'],
        ];
    }

    private function initiateEsewa(Payment $payment, Booking $booking): array
    {
        $transactionUuid = (string) Str::uuid();
        $merchantCode = config('services.esewa.merchant_code');
        $secretKey = config('services.esewa.secret_key');
        $totalAmount = (int) round($payment->amount);

        $payment->update([
            'gateway_txn_id' => $transactionUuid,
        ]);

        $signedFieldNames = 'total_amount,transaction_uuid,product_code';
        $signature = $this->generateEsewaSignature($signedFieldNames, $secretKey, [
            'total_amount' => (string) $totalAmount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $merchantCode,
        ]);

        return [
            'payment_id' => $payment->id,
            'payment_url' => config('services.esewa.sandbox_url') . '/api/epay/main/v2/form',
            'product_code' => $merchantCode,
            'total_amount' => (string) $totalAmount,
            'transaction_uuid' => $transactionUuid,
            'signed_field_names' => $signedFieldNames,
            'signature' => $signature,
        ];
    }

    private function generateEsewaSignature(string $signedFieldNames, string $secretKey, array $fields): string
    {
        $fieldNames = explode(',', $signedFieldNames);
        $pairs = [];
        foreach ($fieldNames as $field) {
            $field = trim($field);
            $pairs[] = $field . '=' . ($fields[$field] ?? '');
        }
        $message = implode(',', $pairs);

        return base64_encode(hash_hmac('sha256', $message, $secretKey, true));
    }

    private function verifyKhalti(array $data): Payment
    {
        $pidx = $data['pidx'];
        $payment = Payment::where('gateway_txn_id', $pidx)->firstOrFail();

        if (($data['status'] ?? '') === 'User canceled') {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => $data,
            ]);
            return $payment->fresh();
        }

        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
        ])->post('https://dev.khalti.com/api/v2/epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        $body = $response->json();

        if ($response->successful() && ($body['status'] ?? '') === 'Completed') {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'gateway_txn_id' => $body['transaction_id'] ?? $pidx,
                'gateway_response' => $data,
            ]);
            $this->handlePaymentSuccess($payment);
        } else {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => $body ?: $data,
            ]);
        }

        return $payment->fresh();
    }

    private function verifyEsewa(array $data): Payment
    {
        $decoded = json_decode(base64_decode($data['data'] ?? ''), true);
        $transactionUuid = $decoded['transaction_uuid'] ?? $data['transaction_uuid'] ?? null;
        $payment = Payment::where('gateway_txn_id', $transactionUuid)->firstOrFail();

        $statusResponse = Http::get(config('services.esewa.sandbox_url') . '/api/epay/transaction/status/', [
            'product_code' => config('services.esewa.merchant_code'),
            'total_amount' => $decoded['total_amount'] ?? $payment->amount,
            'transaction_uuid' => $transactionUuid,
        ]);

        $body = $statusResponse->json();

        if (($body['status'] ?? '') === 'COMPLETE') {
            $payment->update([
                'status' => 'completed',
                'gateway_txn_id' => $decoded['transaction_code'] ?? $transactionUuid,
                'paid_at' => now(),
                'gateway_response' => $decoded,
            ]);
            $this->handlePaymentSuccess($payment);
        } else {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => $decoded,
            ]);
        }

        return $payment->fresh();
    }

    private function refundKhalti(Payment $payment): array
    {
        $transactionId = $payment->gateway_txn_id;

        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
        ])->post("https://dev.khalti.com/api/merchant-transaction/{$transactionId}/refund/", [
            'amount' => (int) round($payment->amount * 100),
        ]);

        $body = $response->json();

        if (!$response->successful()) {
            Log::warning('Khalti refund failed.', ['payment_id' => $payment->id, 'response' => $body]);
        }

        return $body ?? ['status' => 'failed'];
    }

    private function refundEsewa(Payment $payment): array
    {
        Log::warning('eSewa refund not fully implemented. Manual refund required.', [
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
        ]);
        return ['refund_txn_id' => null, 'status' => 'manual'];
    }

    private function handlePaymentSuccess(Payment $payment): void
    {
        $booking = $payment->booking;
        $company = $booking->bike->company;

        if ($payment->type === 'deposit') {
            $booking->update(['deposit_paid_at' => now()]);

            if ($booking->status->value === 'pending_payment') {
                $this->commissionService->snapshotOnConfirm($booking);
            }

            $booking->update(['status' => \App\Enums\BookingStatusEnum::Confirmed]);

            $booking->refresh();

            $this->walletService->credit(
                $company,
                $payment->amount,
                'deposit_credit',
                $booking,
                "Deposit payment for booking {$booking->booking_number}",
            );

            if ((float) $booking->commission_amount > 0) {
                $this->walletService->debit(
                    $company,
                    (float) $booking->commission_amount,
                    'commission_debit',
                    $booking,
                    "Commission deducted for booking {$booking->booking_number}",
                );
            }
        }

        if ($payment->type === 'remaining') {
            $booking->update(['remaining_paid_at' => now()]);

            $this->walletService->credit(
                $company,
                $payment->amount,
                'remaining_credit',
                $booking,
                "Remaining payment for booking {$booking->booking_number}",
            );
        }
    }
}
