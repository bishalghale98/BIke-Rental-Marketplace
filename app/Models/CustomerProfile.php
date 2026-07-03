<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'citizenship_number',
        'permanent_address',
        'current_address',
        'profile_photo',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verification()
    {
        return $this->hasOne(CustomerVerification::class);
    }
}
