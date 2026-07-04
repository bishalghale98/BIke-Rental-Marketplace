<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BikeCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function bikes()
    {
        return $this->hasMany(Bike::class, 'category_id');
    }
}
