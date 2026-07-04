<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BikeImage extends Model
{
    protected $fillable = [
        'bike_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }
}
