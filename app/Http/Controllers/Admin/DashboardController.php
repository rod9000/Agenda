<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Commission;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
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

        $periodStart = $dateRange[0];
        $periodEnd = $dateRange[1];

        $isAdmin = auth()->user()->isAdmin();

        // --- Agregação única de status ---
        $statusAgg = Appointment::whereBetween('start', [$periodStart, $periodEnd])
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'scheduled' OR status = 'confirmed' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'cancelled' OR status = 'no_show' THEN 1 ELSE 0 END) as cancelled
            ")->first();

        $totalInPeriod = (int) ($statusAgg->total ?? 0);
        $completedCount = (int) ($statusAgg->completed ?? 0);
        $pendingCount = (int) ($statusAgg->pending ?? 0);
        $cancelledCount = (int) ($statusAgg->cancelled ?? 0);

        // --- Revenue agregado ---
        $revenue = (float) Appointment::where('status', 'completed')
            ->whereBetween('start', [$periodStart, $periodEnd])
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->sum('appointment_service.price');

        $uniqueCustomers = (int) Appointment::where('status', 'completed')
            ->whereBetween('start', [$periodStart, $periodEnd])
            ->distinct('customer_id')
            ->count('customer_id');

        $avgTicket = $completedCount > 0 ? $revenue / $completedCount : 0;

        // --- Receita Dia/Semana/Mês ---
        $todayRange = [Carbon::today(), Carbon::today()->endOfDay()];
        $weekRange  = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $monthRange = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];

        $revenueDay = $this->revenueInRange($todayRange);
        $revenueWeek = $this->revenueInRange($weekRange);
        $revenueMonth = $this->revenueInRange($monthRange);

        // --- Contagens Dia/Semana/Mês em 1 query agregada ---
        $completedAgg = Appointment::where('status', 'completed')
            ->selectRaw("
                SUM(CASE WHEN start >= ? AND start <= ? THEN 1 ELSE 0 END) as count_day,
                SUM(CASE WHEN start >= ? AND start <= ? THEN 1 ELSE 0 END) as count_week,
                SUM(CASE WHEN start >= ? AND start <= ? THEN 1 ELSE 0 END) as count_month
            ", [
                $todayRange[0], $todayRange[1],
                $weekRange[0], $weekRange[1],
                $monthRange[0], $monthRange[1],
            ])->first();

        $countDay = (int) ($completedAgg->count_day ?? 0);
        $countWeek = (int) ($completedAgg->count_week ?? 0);
        $countMonth = (int) ($completedAgg->count_month ?? 0);

        // --- Gráfico de 7 dias: 1 query ---
        $chartData = $this->weeklyChartData();

        // --- Top 5 serviços ---
        $topServices = Appointment::where('status', 'completed')
            ->whereBetween('start', [$periodStart, $periodEnd])
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->join('services', 'appointment_service.service_id', '=', 'services.id')
            ->select('services.id', 'services.name', 'services.color_hex',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(appointment_service.price) as total_revenue'))
            ->groupBy('services.id', 'services.name', 'services.color_hex')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // --- Atendimentos de hoje ---
        $todayAppointments = Appointment::with(['customer', 'services', 'user'])
            ->whereDate('start', Carbon::today())
            ->orderBy('start')
            ->get();

        // --- Próximos 5 agendamentos ---
        $upcomingAppointments = Appointment::with(['customer', 'services', 'user'])
            ->where('start', '>=', Carbon::now())
            ->whereNotIn('status', ['completed', 'cancelled', 'no_show'])
            ->orderBy('start')
            ->limit(5)
            ->get();

        // --- Aniversariantes ---
        $todayBirthdays = Customer::whereMonth('birth_date', Carbon::now()->month)
            ->whereDay('birth_date', Carbon::now()->day)
            ->get();

        $monthBirthdays = Customer::whereMonth('birth_date', Carbon::now()->month)
            ->orderBy('birth_date')
            ->get();

        $pendingCommissions = Commission::where('paid', false)->sum('value');

        // --- Comparativo com período anterior ---
        $periodLength = $periodStart->diffInDays($periodEnd) + 1;
        $prevPeriodStart = $periodStart->copy()->subDays($periodLength);
        $prevPeriodEnd = $periodStart->copy()->subDay();

        $prevAgg = Appointment::where('status', 'completed')
            ->whereBetween('start', [$prevPeriodStart, $prevPeriodEnd])
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->selectRaw('SUM(appointment_service.price) as revenue, COUNT(DISTINCT appointments.id) as total_count')
            ->first();

        $prevRevenue = (float) ($prevAgg->revenue ?? 0);
        $prevCompletedCount = (int) ($prevAgg->total_count ?? 0);

        $revenueChange = $prevRevenue > 0
            ? round(($revenue - $prevRevenue) / $prevRevenue * 100, 1)
            : ($revenue > 0 ? 100 : 0);

        $completedChange = $prevCompletedCount > 0
            ? round(($completedCount - $prevCompletedCount) / $prevCompletedCount * 100, 1)
            : ($completedCount > 0 ? 100 : 0);

        $totalFinished = $completedCount + $cancelledCount;
        $cancellationRate = $totalFinished > 0 ? round($cancelledCount / $totalFinished * 100, 1) : 0;
        $conversionRate = $totalInPeriod > 0 ? round($completedCount / $totalInPeriod * 100, 1) : 0;

        // --- Dia da semana mais movimentado ---
        $dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        $dayExpr = DB::getDriverName() === 'sqlite'
            ? "strftime('%w', start)"
            : "DAYOFWEEK(start) - 1";

        $dayCounts = Appointment::where('status', 'completed')
            ->whereBetween('start', [$periodStart, $periodEnd])
            ->selectRaw("{$dayExpr} as day_index, COUNT(*) as total")
            ->groupBy('day_index')
            ->orderByDesc('total')
            ->pluck('total', 'day_index');

        $busiestDayIndex = $dayCounts->isNotEmpty() ? $dayCounts->keys()->first() : null;
        $busiestDayName = $busiestDayIndex !== null ? $dayNames[$busiestDayIndex] : '—';
        $busiestDayCount = $dayCounts->get($busiestDayIndex, 0);

        // --- Performance por profissional (admin) ---
        $profPerformance = collect();
        $profRevenue = collect();
        if ($isAdmin) {
            $profPerformance = User::where('active', true)
                ->withCount(['appointments' => function ($q) use ($periodStart, $periodEnd) {
                    $q->where('status', 'completed')
                      ->whereBetween('start', [$periodStart, $periodEnd]);
                }])
                ->get()
                ->filter(fn($u) => $u->appointments_count > 0)
                ->values();

            $profRevenue = Appointment::where('status', 'completed')
                ->whereBetween('start', [$periodStart, $periodEnd])
                ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
                ->join('users', 'appointments.user_id', '=', 'users.id')
                ->select('users.id', 'users.name',
                    DB::raw('COUNT(DISTINCT appointments.id) as total_appointments'),
                    DB::raw('SUM(appointment_service.price) as total_revenue'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_revenue')
                ->get();
        }

        return view('admin.dashboard', compact(
            'revenue', 'completedCount', 'pendingCount', 'cancelledCount',
            'uniqueCustomers', 'avgTicket',
            'todayAppointments', 'upcomingAppointments',
            'todayBirthdays', 'monthBirthdays',
            'period', 'chartData',
            'revenueDay', 'revenueWeek', 'revenueMonth',
            'countDay', 'countWeek', 'countMonth',
            'topServices', 'pendingCommissions',
            'profPerformance', 'isAdmin',
            'revenueChange', 'completedChange',
            'cancellationRate', 'conversionRate', 'totalInPeriod', 'totalFinished',
            'busiestDayName', 'busiestDayCount',
            'profRevenue'
        ));
    }

    private function revenueInRange(array $range): float
    {
        return (float) Appointment::where('status', 'completed')
            ->whereBetween('start', $range)
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->sum('appointment_service.price');
    }

    private function weeklyChartData(): array
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $dayExpr = DB::getDriverName() === 'sqlite'
            ? "strftime('%w', start)"
            : "DAYOFWEEK(start) - 1";

        $revenueByDay = Appointment::where('status', 'completed')
            ->whereBetween('start', [$weekStart, $weekEnd])
            ->join('appointment_service', 'appointments.id', '=', 'appointment_service.appointment_id')
            ->selectRaw("{$dayExpr} as day_index, SUM(appointment_service.price) as total")
            ->groupBy('day_index')
            ->pluck('total', 'day_index');

        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $data[] = [
                'label' => $day->locale('pt-BR')->isoFormat('ddd'),
                'value' => (float) ($revenueByDay->get($i, 0)),
            ];
        }
        return $data;
    }
}
