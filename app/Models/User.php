<?php

namespace App\Models;

use App\Enums\AccountStatusEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'account_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'account_status' => AccountStatusEnum::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(\App\Enums\RoleEnum::Admin);
    }

    public function isCompany(): bool
    {
        return $this->hasRole(\App\Enums\RoleEnum::Company);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(\App\Enums\RoleEnum::Customer);
    }

    public function company()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }
}
