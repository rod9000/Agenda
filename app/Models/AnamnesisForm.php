<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnamnesisForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'answered_by',
        'answered_at',
        'heart_problem',
        'high_pressure',
        'low_pressure',
        'diabetes',
        'epilepsy',
        'cancer',
        'autoimmune',
        'kidney_disease',
        'hepatitis',
        'hiv',
        'pregnant',
        'skin_disease',
        'keloids',
        'isotretinoin',
        'cosmetic_procedure',
        'recent_surgery',
        'pacemaker',
        'dental_implants',
        'allergies',
        'medications',
        'medical_treatment',
        'topical_medication',
        'allergy_description',
        'medication_description',
        'medical_treatment_description',
        'observation',
        'consent',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'heart_problem' => 'boolean',
        'high_pressure' => 'boolean',
        'low_pressure' => 'boolean',
        'diabetes' => 'boolean',
        'epilepsy' => 'boolean',
        'cancer' => 'boolean',
        'autoimmune' => 'boolean',
        'kidney_disease' => 'boolean',
        'hepatitis' => 'boolean',
        'hiv' => 'boolean',
        'pregnant' => 'boolean',
        'skin_disease' => 'boolean',
        'keloids' => 'boolean',
        'isotretinoin' => 'boolean',
        'cosmetic_procedure' => 'boolean',
        'recent_surgery' => 'boolean',
        'pacemaker' => 'boolean',
        'dental_implants' => 'boolean',
        'allergies' => 'boolean',
        'medications' => 'boolean',
        'medical_treatment' => 'boolean',
        'topical_medication' => 'boolean',
        'consent' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function answeredBy()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }
}
