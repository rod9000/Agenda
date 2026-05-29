<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\NotificationLog;
use App\Models\Service;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::where('active', true)->orderBy('name')->get();

        if (auth()->user()->isAdmin()) {
            $users = \App\Models\User::where('active', true)->orderBy('name')->get();
        } else {
            $users = \App\Models\User::where('id', auth()->id())->orderBy('name')->get();
        }

        return view('admin.appointments.index', compact('customers', 'services', 'users'));
    }

    public function calendarData(Request $request)
    {
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        $query = Appointment::with(['customer', 'service', 'user'])
            ->whereBetween('start', [$start, $end]);

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $appointments = $query->get()->map(function ($app) {
            $statusColors = [
                'scheduled'  => '#3b82f6',
                'confirmed'  => '#22c55e',
                'in_progress' => '#eab308',
                'completed'  => '#22c55e',
                'cancelled'  => '#ef4444',
                'no_show'    => '#f97316',
            ];

            return [
                'id'              => $app->id,
                'title'           => $app->customer->name . ' - ' . $app->service->name,
                'start'           => $app->start->toIso8601String(),
                'end'             => $app->end->toIso8601String(),
                'backgroundColor' => $statusColors[$app->status] ?? '#3b82f6',
                'borderColor'     => $statusColors[$app->status] ?? '#3b82f6',
                'extendedProps'   => [
                    'customer'    => $app->customer->name,
                    'customer_id' => $app->customer_id,
                    'service'     => $app->service->name,
                    'service_id'  => $app->service_id,
                    'user_id'     => $app->user_id,
                    'status'      => $app->status,
                    'price'       => $app->service->price,
                    'phone'       => $app->customer->phone,
                    'notes'       => $app->notes,
                    'user'        => $app->user->name,
                ],
            ];
        });

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id'     => 'required|exists:users,id',
            'service_id'  => 'required|exists:services,id',
            'start'       => 'required|date',
            'end'         => 'required|date|after:start',
            'notes'       => 'nullable|string',
        ]);

        if (!auth()->user()->isAdmin()) {
            $data['user_id'] = auth()->id();
        }

        $data['status'] = 'scheduled';

        $appointment = Appointment::create($data);

        $this->sendWhatsAppConfirmation($appointment);

        return response()->json(['success' => true, 'appointment' => $appointment->load(['customer', 'service', 'user'])]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'user_id'     => 'required|exists:users,id',
            'service_id'  => 'required|exists:services,id',
            'start'       => 'required|date',
            'end'         => 'required|date|after:start',
            'status'      => 'nullable|string|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'notes'       => 'nullable|string',
        ];

        if (!auth()->user()->isAdmin()) {
            $rules['user_id'] = 'required|in:' . auth()->id();
        }

        $data = $request->validate($rules);

        if (!isset($data['status'])) {
            $data['status'] = $appointment->status;
        }

        $appointment->update($data);

        return response()->json(['success' => true, 'appointment' => $appointment->fresh()->load(['customer', 'service', 'user'])]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['success' => true]);
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date|after:start',
        ]);

        $appointment->update($data);

        return response()->json(['success' => true]);
    }

    private function sendWhatsAppConfirmation(Appointment $appointment)
    {
        try {
            $wa = new WhatsAppService();
            $msg = "Olá {$appointment->customer->name}, seu agendamento foi confirmado!\n"
                 . "Serviço: {$appointment->service->name}\n"
                 . "Data: {$appointment->start->format('d/m/Y H:i')}\n"
                 . "Duração: {$appointment->service->duration_min} min\n"
                 . "Valor: R$ " . number_format($appointment->service->price, 2, ',', '.');

            $wa->send($appointment->customer->phone, $msg);
        } catch (\Exception $e) {
            \Log::error('WhatsApp send failed: ' . $e->getMessage());
        }
    }
}
