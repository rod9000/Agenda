<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Customer;
use Illuminate\Console\Command;

class RecalculateLoyaltyPoints extends Command
{
    protected $signature = 'loyalty:recalculate';
    protected $description = 'Recalcula pontos de fidelidade baseado em appointments concluídos';

    public function handle()
    {
        $bar = $this->output->createProgressBar(Customer::count());
        $bar->start();

        foreach (Customer::lazy() as $customer) {
            $totalPrice = (float) Appointment::where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
                ->sum('appointment_service.price');

            $totalVisits = Appointment::where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->count();

            $customer->points = (int) floor($totalPrice);
            $customer->total_visits = $totalVisits;
            $customer->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Pontos recalculados para todos os clientes!');
    }
}
