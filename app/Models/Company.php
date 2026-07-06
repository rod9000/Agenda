<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'whatsapp',
        'cnpj',
        'evolution_api_url',
        'evolution_api_key',
        'evolution_instance_name',
        'whatsapp_type',
        'webhook_enabled',
        'bot_enabled',
        'welcome_message',
        'off_hours_message',
        'evolution_webhook_url',
        'trial_starts_at',
        'trial_ends_at',
        'active',
    ];

    protected $casts = [
        'trial_starts_at' => 'datetime',
        'trial_ends_at' => 'date',
        'active' => 'boolean',
    ];

    public function evolutionConfigured(): bool
    {
        return !empty($this->evolution_api_url)
            && !empty($this->evolution_api_key)
            && !empty($this->evolution_instance_name);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getDefaultWelcomeMessage(): string
    {
        return $this->welcome_message
            ?: "Olá! Bem-vindo(a) à {$this->name}!\n\nComo posso te ajudar?";
    }

    public function getDefaultOffHoursMessage(): string
    {
        return $this->off_hours_message
            ?: "Olá! No momento estamos fora do horário de atendimento.\n\nDeixe sua mensagem que retornamos! 😊";
    }

    public function isBusinessOpen(): bool
    {
        $now = now()->setTimezone('America/Sao_Paulo');
        $dayOfWeek = $now->dayOfWeek;

        $workingHours = WorkingHour::where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get();

        if ($workingHours->isEmpty()) {
            return false;
        }

        $currentTime = $now->format('H:i:s');
        foreach ($workingHours as $wh) {
            $start = $wh->start_time;
            $end = $wh->end_time;

            if ($end === '00:00:00') {
                if ($currentTime >= $start) {
                    return true;
                }
            } else {
                if ($currentTime >= $start && $currentTime <= $end) {
                    return true;
                }
            }
        }

        return false;
    }
}
