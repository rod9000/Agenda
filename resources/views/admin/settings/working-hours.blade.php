@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Horários de Funcionamento</h2>
        <a href="{{ route('admin.settings.blocked-slots') }}" class="btn-pastel-secondary">Bloqueios</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @foreach($users as $user)
        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 mb-4">{{ $user->name }}</h3>
            <div class="overflow-x-auto">
                <table class="table-pastel w-full">
                    <thead>
                        <tr>
                            <th>Dia</th>
                            <th>Início</th>
                            <th>Fim</th>
                            <th>Ativo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(range(0, 6) as $day)
                        <tr>
                            <td class="font-medium">{{ $days[$day] }}</td>
                            <form method="POST" action="{{ route('admin.settings.working-hours.store') }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="day_of_week" value="{{ $day }}">
                                <td>
                                    <input type="time" name="start_time" value="{{ old('start_time', $hours[$user->id][$day]?->start_time ?? '08:00') }}" class="input-pastel">
                                </td>
                                <td>
                                    <input type="time" name="end_time" value="{{ old('end_time', $hours[$user->id][$day]?->end_time ?? '18:00') }}" class="input-pastel">
                                </td>
                                <td>
                                    <input type="checkbox" name="active" value="1" {{ ($hours[$user->id][$day]?->active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-300 text-brand-600">
                                </td>
                                <td>
                                    <button type="submit" class="text-sm text-brand-600 hover:text-brand-800 font-medium">Salvar</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection