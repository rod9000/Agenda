<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AppointmentsExport;
use App\Models\Appointment;
use App\Models\Commission;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $monthlyRevenue = [];
        $monthlyAppointments = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $revenue = Appointment::where('status', 'completed')
                ->whereBetween('start', [$start, $end])
                ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
                ->sum('appointment_service.price');

            $monthlyRevenue[] = [
                'label' => $date->locale('pt-BR')->isoFormat('MMM/YY'),
                'value' => (float) $revenue,
            ];

            $count = Appointment::where('status', 'completed')
                ->whereBetween('start', [$start, $end])
                ->count();

            $monthlyAppointments[] = [
                'label' => $date->locale('pt-BR')->isoFormat('MMM/YY'),
                'value' => $count,
            ];
        }

        $topServices = Appointment::where('appointments.status', 'completed')
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->join('services', 'appointment_service.service_id', '=', 'services.id')
            ->select('services.name', 'services.id', DB::raw('COUNT(*) as total'), DB::raw('SUM(appointment_service.price) as total_price'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $commissions = Commission::with('user')
            ->selectRaw('user_id, SUM(value) as total, COUNT(*) as count, SUM(CASE WHEN paid THEN value ELSE 0 END) as paid_total')
            ->groupBy('user_id')
            ->get();

        $paymentMethods = Payment::selectRaw('method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('method')
            ->get();

        $totalRevenue = Appointment::where('status', 'completed')
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->sum('appointment_service.price');

        $totalAppointments = Appointment::where('status', 'completed')->count();

        $avgPerMonth = $totalAppointments > 0
            ? round($totalAppointments / max(1, Carbon::now()->diffInMonths(Appointment::min('start')) ?: 1), 1)
            : 0;

        return view('admin.reports.index', compact(
            'monthlyRevenue', 'monthlyAppointments', 'topServices',
            'commissions', 'paymentMethods', 'totalRevenue',
            'totalAppointments', 'avgPerMonth'
        ));
    }

    public function exportCsv(Request $request)
    {
        $start = $request->get('start') ? Carbon::parse($request->get('start'))->startOfDay() : null;
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : null;
        $userId = $request->get('user_id');
        $status = $request->get('status');

        $export = new AppointmentsExport();
        $csv = $export->exportCsv($start, $end, $userId, $status);

        $filename = 'agendamentos-' . now()->format('Y-m-d') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
