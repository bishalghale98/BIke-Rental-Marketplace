<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
        'company_id',
        'bank_name',
        'account_name',
        'account_number',
        'branch',
        'is_default',
        'qr_code',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
