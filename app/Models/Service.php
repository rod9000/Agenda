<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_min',
        'price',
        'estimated_product_cost',
        'color_hex',
        'description',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'estimated_product_cost' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
