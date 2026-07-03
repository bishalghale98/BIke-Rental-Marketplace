<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'owner_name',
        'registration_number',
        'pan_number',
        'address',
        'contact_number',
        'description',
        'logo',
        'cover_image',
        'opening_hours',
        'social_links',
        'verification_status',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'opening_hours' => 'array',
            'rating' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bikes()
    {
        return $this->hasMany(Bike::class, 'company_id');
    }
}
