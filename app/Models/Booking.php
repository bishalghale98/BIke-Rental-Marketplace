<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'customer_id',
        'bike_id',
        'start_date',
        'end_date',
        'hourly_price',
        'daily_price',
        'weekly_price',
        'hourly_discount',
        'daily_discount',
        'weekly_discount',
        'total_hours',
        'total_days',
        'total_weeks',
        'subtotal',
        'discount_amount',
        'total_amount',
        'commission_percent',
        'commission_amount',
        'company_earnings',
        'status',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
        'refund_amount',
        'refund_paid_at',
        'late_fee',
        'late_fee_paid_at',
        'extended_until',
        'deposit_paid_at',
        'remaining_paid_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'hourly_price' => 'decimal:2',
            'daily_price' => 'decimal:2',
            'weekly_price' => 'decimal:2',
            'hourly_discount' => 'decimal:2',
            'daily_discount' => 'decimal:2',
            'weekly_discount' => 'decimal:2',
            'total_hours' => 'decimal:2',
            'total_days' => 'decimal:2',
            'total_weeks' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'commission_percent' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'company_earnings' => 'decimal:2',
            'status' => BookingStatusEnum::class,
            'cancelled_at' => 'datetime',
            'refund_amount' => 'decimal:2',
            'refund_paid_at' => 'datetime',
            'late_fee' => 'decimal:2',
            'late_fee_paid_at' => 'datetime',
            'extended_until' => 'datetime',
            'deposit_paid_at' => 'datetime',
            'remaining_paid_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function extensionRequests()
    {
        return $this->hasMany(ExtensionRequest::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getEffectiveEndDateAttribute()
    {
        return $this->extended_until ?? $this->end_date;
    }

    public function isLate(): bool
    {
        return now()->gt($this->effective_end_date) && $this->status === BookingStatusEnum::PickedUp;
    }

    public function scopePendingPayment($query)
    {
        return $query->where('status', BookingStatusEnum::PendingPayment);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            BookingStatusEnum::DepositPaid,
            BookingStatusEnum::Confirmed,
            BookingStatusEnum::PickedUp,
        ]);
    }

    public function getDepositAmountAttribute()
    {
        return round($this->total_amount * (config('marketplace.deposit_percentage') / 100), 2);
    }

    public function getRemainingAmountAttribute()
    {
        return round($this->total_amount - $this->deposit_amount, 2);
    }
}
