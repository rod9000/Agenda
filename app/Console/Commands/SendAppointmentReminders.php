<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\NotificationLog;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send WhatsApp reminders for upcoming appointments';

    public function handle()
    {
        $now = Carbon::now();
        $reminderTime = $now->copy()->addHour();

        $appointments = Appointment::with(['customer', 'service'])
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->whereBetween('start', [$now, $reminderTime])
            ->whereDoesntHave('notifications', function ($q) {
                $q->where('type', 'reminder');
            })
            ->get();

        $wa = new WhatsAppService();

        foreach ($appointments as $appointment) {
            $msg = "🔔 Lembrete: {$appointment->customer->name}, seu horário é às "
                 . $appointment->start->format('H:i') . "h!\n"
                 . "Serviço: {$appointment->service->name}\n"
                 . "Local: Clínica de Estética";

            $sent = $wa->send($appointment->customer->phone, $msg);

            NotificationLog::create([
                'appointment_id' => $appointment->id,
                'customer_id'    => $appointment->customer_id,
                'type'           => 'reminder',
                'recipient'      => $appointment->customer->phone,
                'message'        => $msg,
                'status'         => $sent ? 'sent' : 'failed',
                'sent_at'        => $sent ? Carbon::now() : null,
            ]);

            $this->info("Reminder sent to {$appointment->customer->name}");
        }

        $this->info("Total reminders sent: {$appointments->count()}");
    }
}
