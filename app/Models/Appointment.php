<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'service_id',
        'start',
        'end',
        'status',
        'notes',
        'confirmation_token',
        'confirmed_at',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($appointment) {
            if (!$appointment->confirmation_token) {
                $appointment->confirmation_token = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('price', 'duration_min');
    }

    public function notifications()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function hasPayment()
    {
        return $this->payment()->exists();
    }
}
