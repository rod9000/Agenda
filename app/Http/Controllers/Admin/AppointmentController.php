<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Commission;
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

        $query = Appointment::with(['customer', 'services', 'user', 'payment'])
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

            $serviceNames = $app->services->pluck('name')->implode(' + ');
            $totalPrice = $app->services->sum('pivot.price');

            return [
                'id'              => $app->id,
                'title'           => $app->customer->name . ' - ' . $serviceNames,
                'start'           => $app->start->toIso8601String(),
                'end'             => $app->end->toIso8601String(),
                'backgroundColor' => $statusColors[$app->status] ?? '#3b82f6',
                'borderColor'     => $statusColors[$app->status] ?? '#3b82f6',
                'extendedProps'   => [
                    'customer'    => $app->customer->name,
                    'customer_id' => $app->customer_id,
                    'service'     => $serviceNames,
                    'service_id'  => $app->service_id,
                    'service_ids' => $app->services->pluck('id')->toArray(),
                    'user_id'     => $app->user_id,
                    'status'      => $app->status,
                    'price'       => $totalPrice,
                    'phone'       => $app->customer->phone,
                    'notes'       => $app->notes,
                    'user'        => $app->user->name,
                    'payment'     => $app->payment ? ['method' => $app->payment->method, 'amount' => $app->payment->amount] : null,
                ],
            ];
        });

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'user_id'            => 'required|exists:users,id',
            'service_ids'        => 'required|array|min:1',
            'service_ids.*'      => 'exists:services,id',
            'start'              => 'required|date',
            'end'                => 'nullable|date|after:start',
            'notes'              => 'nullable|string',
            'recurring_frequency' => 'nullable|string|in:daily,weekly,biweekly,monthly',
            'recurring_until'    => 'nullable|date|after:start',
        ]);

        if (!auth()->user()->isAdmin()) {
            $data['user_id'] = auth()->id();
        }

        $services = Service::whereIn('id', $data['service_ids'])->get();
        $maxDuration = $services->max('duration_min');
        $data['service_id'] = $services->first()->id;

        if (!$data['end']) {
            $data['end'] = Carbon::parse($data['start'])->addMinutes($maxDuration);
        }

        $data['status'] = 'scheduled';

        $appointment = Appointment::create($data);

        $pivotData = [];
        foreach ($services as $service) {
            $pivotData[$service->id] = [
                'price'        => $service->price,
                'duration_min' => $service->duration_min,
            ];
        }
        $appointment->services()->sync($pivotData);

        if (!empty($data['recurring_frequency']) && !empty($data['recurring_until'])) {
            $frequency = $data['recurring_frequency'];
            $until = Carbon::parse($data['recurring_until']);
            $start = Carbon::parse($data['start']);

            $intervals = [
                'daily' => 1,
                'weekly' => 7,
                'biweekly' => 14,
                'monthly' => 30,
            ];

            $days = $intervals[$frequency] ?? 7;
            $current = $start->copy()->addDays($days);

            while ($current->lte($until)) {
                $recurringEnd = Carbon::parse($data['end'])->addDays($current->diffInDays($start));

                $child = Appointment::create([
                    'customer_id' => $data['customer_id'],
                    'user_id'     => $data['user_id'],
                    'service_id'  => $data['service_id'],
                    'start'       => $current,
                    'end'         => $recurringEnd,
                    'status'      => 'scheduled',
                    'notes'       => $data['notes'] ?? null,
                    'parent_id'   => $appointment->id,
                ]);

                $child->services()->sync($pivotData);

                $current->addDays($days);
            }
        }

        $this->sendWhatsAppConfirmation($appointment);

        return response()->json(['success' => true, 'appointment' => $appointment->load(['customer', 'services', 'user'])]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $rules = [
            'customer_id'   => 'sometimes|required|exists:customers,id',
            'user_id'       => 'sometimes|required|exists:users,id',
            'service_ids'   => 'sometimes|required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'start'         => 'sometimes|required|date',
            'end'           => 'sometimes|required|date|after:start',
            'status'        => 'nullable|string|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'notes'         => 'nullable|string',
        ];

        if (!auth()->user()->isAdmin()) {
            $rules['user_id'] = 'sometimes|required|in:' . auth()->id();
        }

        $data = $request->validate($rules);

        if (!empty($data['service_ids'])) {
            $services = Service::whereIn('id', $data['service_ids'])->get();
            $data['service_id'] = $services->first()->id;

            $pivotData = [];
            foreach ($services as $service) {
                $pivotData[$service->id] = [
                    'price'        => $service->price,
                    'duration_min' => $service->duration_min,
                ];
            }
        }

        $wasCompleted = $appointment->status !== 'completed' && ($data['status'] ?? '') === 'completed';
        $wasCancelled = $appointment->status !== 'cancelled' && ($data['status'] ?? '') === 'cancelled';

        $appointment->update($data);

        if (!empty($pivotData)) {
            $appointment->services()->sync($pivotData);
        }

        $totalPrice = $appointment->services()->sum('appointment_service.price');

        if ($wasCompleted && !$appointment->hasPayment()) {
            $appointment->payment()->create([
                'amount'        => $totalPrice,
                'method'        => 'dinheiro',
                'paid_at'       => now(),
                'registered_by' => auth()->id(),
            ]);
        }

        if ($wasCompleted) {
            $allServices = $appointment->services()->with('products')->get();
            $totalCommission = 0;
            foreach ($allServices as $service) {
                $totalCommission += $service->calculateCommission($service->pivot->price);
            }

            if ($totalCommission > 0) {
                Commission::updateOrCreate(
                    ['appointment_id' => $appointment->id],
                    [
                        'user_id' => $appointment->user_id,
                        'value'   => $totalCommission,
                        'paid'    => false,
                    ]
                );
            }

            $productsDeducted = [];
            foreach ($allServices as $service) {
                foreach ($service->products as $product) {
                    if (isset($productsDeducted[$product->id])) continue;
                    $productsDeducted[$product->id] = true;

                    $qty = $product->pivot->quantity;

                    if ($product->pivot->is_per_session) {
                        $jaConsumidoHoje = Appointment::where('customer_id', $appointment->customer_id)
                            ->where('id', '!=', $appointment->id)
                            ->whereIn('status', ['completed', 'in_progress'])
                            ->whereDate('start', today())
                            ->whereHas('services.products', fn($q) => $q->where('product_id', $product->id))
                            ->exists();

                        if ($jaConsumidoHoje) {
                            continue;
                        }
                    }

                    $product->removeStock($qty, "Procedimento {$service->name} - {$appointment->customer->name}");
                }
            }
        }

        return response()->json(['success' => true, 'appointment' => $appointment->fresh()->load(['customer', 'services', 'user', 'payment'])]);
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
            $appointment->load('services');
            $wa = new WhatsAppService();
            $serviceList = $appointment->services->map(fn($s) => $s->name . ' (' . $s->pivot->duration_min . 'min)')->implode("\n");
            $totalPrice = $appointment->services->sum('pivot.price');
            $confirmLink = url('/confirmar/' . $appointment->confirmation_token);
            $msg = "Olá {$appointment->customer->name}, seu agendamento foi confirmado!\n"
                 . "Serviços:\n{$serviceList}\n"
                 . "Data: {$appointment->start->format('d/m/Y H:i')}\n"
                 . "Valor: R$ " . number_format($totalPrice, 2, ',', '.')
                 . "\n\nConfirme sua presença clicando no link abaixo:\n{$confirmLink}";

            $wa->send($appointment->customer->phone, $msg);
        } catch (\Exception $e) {
            \Log::error('WhatsApp send failed: ' . $e->getMessage());
        }
    }
}
