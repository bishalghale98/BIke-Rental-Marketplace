<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bike extends Model
{
    use SoftDeletes;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Bike $bike) {
            if (empty($bike->slug)) {
                $bike->slug = str($bike->name)->slug() . '-' . str()->random(5);
            }
        });
    }

    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'slug',
        'brand',
        'model',
        'year',
        'engine_capacity',
        'fuel_type',
        'transmission',
        'mileage',
        'color',
        'bike_number',
        'registration_number',
        'vin',
        'description',
        'features',
        'specifications',
        'rental_rules',
        'hourly_price',
        'daily_price',
        'weekly_price',
        'status',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'features' => 'array',
            'specifications' => 'array',
            'rental_rules' => 'array',
            'hourly_price' => 'decimal:2',
            'daily_price' => 'decimal:2',
            'weekly_price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(BikeCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(BikeImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(BikeImage::class)->where('is_primary', true)->ofMany();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Booking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->where('is_available', true);
    }
}
