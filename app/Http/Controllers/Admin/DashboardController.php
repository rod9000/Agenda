<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');

        $dateRange = match ($period) {
            'today'    => [Carbon::today(), Carbon::today()->endOfDay()],
            'week'     => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'month'    => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            default    => [Carbon::today(), Carbon::today()->endOfDay()],
        };

        $completedQuery = Appointment::where('status', 'completed')
            ->whereBetween('start', $dateRange);

        $revenue = $completedQuery->clone()
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->sum('appointment_service.price');

        $completedCount = $completedQuery->clone()->count();

        $pendingCount = Appointment::whereIn('status', ['scheduled', 'confirmed'])->count();

        $todayAppointments = Appointment::with(['customer', 'services', 'user'])
            ->whereDate('start', Carbon::today())
            ->orderBy('start')
            ->get();

        $birthdayCount = Customer::whereMonth('birth_date', Carbon::now()->month)
            ->whereDay('birth_date', Carbon::now()->day)
            ->count();

        $services = Service::where('active', true)->get();

        $chartData = [];
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::now()->startOfWeek()->addDays($i);
            $dayTotal = Appointment::where('status', 'completed')
                ->whereDate('start', $day)
                ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
                ->sum('appointment_service.price');
            $chartData[] = [
                'label' => $day->locale('pt-BR')->isoFormat('ddd'),
                'value' => (float) $dayTotal,
            ];
        }

        $revenueDay = Appointment::where('status', 'completed')
            ->whereDate('start', Carbon::today())
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $revenueWeek = Appointment::where('status', 'completed')
            ->whereBetween('start', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $revenueMonth = Appointment::where('status', 'completed')
            ->whereBetween('start', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $countDay = Appointment::where('status', 'completed')
            ->whereDate('start', Carbon::today())
            ->count();

        $countWeek = Appointment::where('status', 'completed')
            ->whereBetween('start', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $countMonth = Appointment::where('status', 'completed')
            ->whereBetween('start', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        return view('admin.dashboard', compact(
            'revenue', 'completedCount', 'pendingCount',
            'todayAppointments', 'birthdayCount',
            'period', 'services', 'chartData',
            'revenueDay', 'revenueWeek', 'revenueMonth',
            'countDay', 'countWeek', 'countMonth'
        ));
    }
}
