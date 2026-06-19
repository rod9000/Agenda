<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'birth_date',
        'email',
        'photo',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function anamnesisForms()
    {
        return $this->hasMany(AnamnesisForm::class);
    }

    public function latestAnamnesis()
    {
        return $this->hasOne(AnamnesisForm::class)->latestOfMany();
    }
}
