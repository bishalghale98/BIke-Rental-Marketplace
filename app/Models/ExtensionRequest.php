<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionRequest extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'requested_end_date',
        'reason',
        'status',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_end_date' => 'datetime',
            'handled_at' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}
