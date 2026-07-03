<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyVerification extends Model
{
    protected $fillable = [
        'company_id',
        'registration_certificate',
        'pan_certificate',
        'owner_citizenship',
        'owner_photo',
        'status',
        'rejected_reason',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
