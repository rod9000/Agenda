<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Appointment;

class AppointmentObserver
{
    public function created(Appointment $appointment)
    {
        $appointment->load('customer', 'service');
        ActivityLog::log('created', $appointment,
            "Agendamento de '{$appointment->customer?->name}' para '{$appointment->service?->name}' em {$appointment->start?->format('d/m/Y H:i')}.",
            null, $appointment->toArray());
    }

    public function updated(Appointment $appointment)
    {
        $old = $appointment->getOriginal();
        $changes = [];
        foreach ($appointment->getChanges() as $key => $value) {
            if ($key !== 'updated_at') {
                $oldVal = $old[$key] ?? '';
                if ($key === 'start' || $key === 'end') {
                    $oldVal = $old[$key] ? date('d/m/Y H:i', strtotime($old[$key])) : '';
                    $value = $value ? date('d/m/Y H:i', strtotime($value)) : '';
                }
                if ($key === 'status') {
                    $labels = ['scheduled' => 'Agendado', 'confirmed' => 'Confirmado', 'in_progress' => 'Em Andamento', 'completed' => 'Concluído', 'cancelled' => 'Cancelado', 'no_show' => 'Não Compareceu'];
                    $oldVal = $labels[$oldVal] ?? $oldVal;
                    $value = $labels[$value] ?? $value;
                }
                $changes[] = "$key: {$oldVal} → {$value}";
            }
        }
        if ($changes) {
            ActivityLog::log('updated', $appointment, "Agendamento #{$appointment->id} atualizado: " . implode(', ', $changes), $old, $appointment->toArray());
        }
    }

    public function deleted(Appointment $appointment)
    {
        $appointment->load('customer');
        ActivityLog::log('deleted', $appointment,
            "Agendamento #{$appointment->id} de '{$appointment->customer?->name}' foi removido.",
            $appointment->toArray(), null);
    }
}
