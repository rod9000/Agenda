<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedSlot;
use App\Models\User;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function workingHours()
    {
        $users = User::where('active', true)->orderBy('name')->get();
        $days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

        $hours = [];
        foreach ($users as $user) {
            foreach (range(0, 6) as $day) {
                $wh = WorkingHour::where('user_id', $user->id)->where('day_of_week', $day)->first();
                $hours[$user->id][$day] = $wh;
            }
        }

        return view('admin.settings.working-hours', compact('users', 'days', 'hours'));
    }

    public function workingHoursStore(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after:start_time',
            'active'      => 'boolean',
        ]);

        WorkingHour::updateOrCreate(
            ['user_id' => $data['user_id'], 'day_of_week' => $data['day_of_week']],
            [
                'start_time' => $data['start_time'] ?? null,
                'end_time'   => $data['end_time'] ?? null,
                'active'     => $request->boolean('active', true),
            ]
        );

        return redirect()->back()->with('success', 'Horário atualizado com sucesso!');
    }

    public function blockedSlots()
    {
        $users = User::where('active', true)->orderBy('name')->get();
        $slots = BlockedSlot::with('user')->where('start', '>=', Carbon::now())->orderBy('start')->paginate(20);

        return view('admin.settings.blocked-slots', compact('users', 'slots'));
    }

    public function blockedSlotsStore(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'start'   => 'required|date',
            'end'     => 'required|date|after:start',
            'reason'  => 'nullable|string|max:255',
        ]);

        BlockedSlot::create($data);

        return redirect()->back()->with('success', 'Bloqueio cadastrado com sucesso!');
    }

    public function blockedSlotsDestroy(BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();
        return redirect()->back()->with('success', 'Bloqueio removido!');
    }
}
