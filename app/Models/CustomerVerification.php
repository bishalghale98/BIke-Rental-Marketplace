<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerVerification extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry_date',
        'license_front_image',
        'license_back_image',
        'citizenship_front',
        'citizenship_back',
        'selfie',
        'status',
        'rejected_reason',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'license_expiry_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
