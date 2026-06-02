@extends('layouts.app')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">{{ isset($anamnesis) ? 'Editar Ficha de Anamnese' : 'Nova Ficha de Anamnese' }}</h2>
        <a href="{{ route('admin.anamnesis.index') }}" class="btn-pastel-secondary w-full sm:w-auto justify-center text-sm">← Voltar</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel">
            <form method="POST" action="{{ isset($anamnesis) ? route('admin.anamnesis.update', $anamnesis) : route('admin.anamnesis.store') }}">
                @csrf
                @if(isset($anamnesis)) @method('PUT') @endif

                <div class="mb-6">
                    <label class="block text-sm hidpi:text-base font-medium text-brand-700 mb-1">Cliente</label>
                    <select name="customer_id" required class="input-pastel w-full hidpi:text-base hidpi:py-2.5">
                        <option value="">Selecione um cliente...</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $selectedCustomerId ?? $anamnesis->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} — {{ $c->cpf }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="text-rose-500 text-xs hidpi:text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <h3 class="text-base hidpi:text-lg font-semibold text-brand-800 mb-3 pb-2 border-b border-brand-100 sticky top-0 bg-white/90 backdrop-blur-sm z-10">Saúde Geral</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 hidpi:gap-4 mb-6">
                    @php
                        $healthQuestions = [
                            'heart_problem' => 'Problema cardíaco?',
                            'high_pressure' => 'Pressão alta?',
                            'low_pressure' => 'Pressão baixa?',
                            'diabetes' => 'Diabetes?',
                            'epilepsy' => 'Epilepsia?',
                            'cancer' => 'Câncer?',
                            'autoimmune' => 'Doença autoimune?',
                            'kidney_disease' => 'Doença renal?',
                            'hepatitis' => 'Hepatite?',
                            'hiv' => 'HIV?',
                            'pregnant' => 'Gestante ou lactante?',
                        ];
                    @endphp
                    @foreach($healthQuestions as $field => $label)
                        <label class="flex items-center gap-3 hidpi:gap-4 p-3 hidpi:p-4 sm:p-2 lg:p-3 rounded-lg border border-brand-100 bg-brand-50/20 hover:bg-brand-50/50 cursor-pointer active:bg-brand-100/50 transition-colors">
                            <input type="hidden" name="{{ $field }}" value="0">
                            <input type="checkbox" name="{{ $field }}" value="1" class="w-5 h-5 hidpi:w-6 hidpi:h-6 sm:w-4 sm:h-4 text-brand-600 border-brand-300 rounded shrink-0" {{ old($field, $anamnesis->$field ?? false) ? 'checked' : '' }}>
                            <span class="text-sm sm:text-xs lg:text-sm hidpi:text-base text-stone-700 leading-tight">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <h3 class="text-base hidpi:text-lg font-semibold text-brand-800 mb-3 pb-2 border-b border-brand-100 sticky top-0 bg-white/90 backdrop-blur-sm z-10">Pele e Procedimentos</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 hidpi:gap-4 mb-6">
                    @php
                        $skinQuestions = [
                            'skin_disease' => 'Doença de pele?',
                            'keloids' => 'Queloide?',
                            'isotretinoin' => 'Uso de Roacutan / Isotretinoína?',
                            'cosmetic_procedure' => 'Procedimento estético recente?',
                        ];
                    @endphp
                    @foreach($skinQuestions as $field => $label)
                        <label class="flex items-center gap-3 hidpi:gap-4 p-3 hidpi:p-4 sm:p-2 lg:p-3 rounded-lg border border-brand-100 bg-brand-50/20 hover:bg-brand-50/50 cursor-pointer active:bg-brand-100/50 transition-colors">
                            <input type="hidden" name="{{ $field }}" value="0">
                            <input type="checkbox" name="{{ $field }}" value="1" class="w-5 h-5 hidpi:w-6 hidpi:h-6 sm:w-4 sm:h-4 text-brand-600 border-brand-300 rounded shrink-0" {{ old($field, $anamnesis->$field ?? false) ? 'checked' : '' }}>
                            <span class="text-sm sm:text-xs lg:text-sm hidpi:text-base text-stone-700 leading-tight">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <h3 class="text-base hidpi:text-lg font-semibold text-brand-800 mb-3 pb-2 border-b border-brand-100 sticky top-0 bg-white/90 backdrop-blur-sm z-10">Cirurgias e Implantes</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 hidpi:gap-4 mb-6">
                    @php
                        $surgeryQuestions = [
                            'recent_surgery' => 'Cirurgia recente?',
                            'pacemaker' => 'Marcapasso?',
                            'dental_implants' => 'Implante dentário?',
                        ];
                    @endphp
                    @foreach($surgeryQuestions as $field => $label)
                        <label class="flex items-center gap-3 hidpi:gap-4 p-3 hidpi:p-4 sm:p-2 lg:p-3 rounded-lg border border-brand-100 bg-brand-50/20 hover:bg-brand-50/50 cursor-pointer active:bg-brand-100/50 transition-colors">
                            <input type="hidden" name="{{ $field }}" value="0">
                            <input type="checkbox" name="{{ $field }}" value="1" class="w-5 h-5 hidpi:w-6 hidpi:h-6 sm:w-4 sm:h-4 text-brand-600 border-brand-300 rounded shrink-0" {{ old($field, $anamnesis->$field ?? false) ? 'checked' : '' }}>
                            <span class="text-sm sm:text-xs lg:text-sm hidpi:text-base text-stone-700 leading-tight">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <h3 class="text-base hidpi:text-lg font-semibold text-brand-800 mb-3 pb-2 border-b border-brand-100 sticky top-0 bg-white/90 backdrop-blur-sm z-10">Medicamentos e Tratamentos</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 hidpi:gap-4 mb-4">
                    @php
                        $medQuestions = [
                            'allergies' => 'Alergias?',
                            'medications' => 'Uso de medicamentos?',
                            'medical_treatment' => 'Tratamento médico em andamento?',
                            'topical_medication' => 'Uso de medicamento tópico?',
                        ];
                    @endphp
                    @foreach($medQuestions as $field => $label)
                        <label class="flex items-center gap-3 hidpi:gap-4 p-3 hidpi:p-4 sm:p-2 lg:p-3 rounded-lg border border-brand-100 bg-brand-50/20 hover:bg-brand-50/50 cursor-pointer active:bg-brand-100/50 transition-colors">
                            <input type="hidden" name="{{ $field }}" value="0">
                            <input type="checkbox" name="{{ $field }}" value="1" class="w-5 h-5 hidpi:w-6 hidpi:h-6 sm:w-4 sm:h-4 text-brand-600 border-brand-300 rounded shrink-0" {{ old($field, $anamnesis->$field ?? false) ? 'checked' : '' }}>
                            <span class="text-sm sm:text-xs lg:text-sm hidpi:text-base text-stone-700 leading-tight">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="space-y-4 hidpi:space-y-5 mb-6">
                    <div>
                        <label class="block text-sm hidpi:text-base font-medium text-brand-700">Se sim, quais alergias?</label>
                        <textarea name="allergy_description" rows="2" class="input-pastel w-full hidpi:text-base">{{ old('allergy_description', $anamnesis->allergy_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm hidpi:text-base font-medium text-brand-700">Se sim, quais medicamentos?</label>
                        <textarea name="medication_description" rows="2" class="input-pastel w-full hidpi:text-base">{{ old('medication_description', $anamnesis->medication_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm hidpi:text-base font-medium text-brand-700">Se sim, qual tratamento?</label>
                        <textarea name="medical_treatment_description" rows="2" class="input-pastel w-full hidpi:text-base">{{ old('medical_treatment_description', $anamnesis->medical_treatment_description ?? '') }}</textarea>
                    </div>
                </div>

                <h3 class="text-base hidpi:text-lg font-semibold text-brand-800 mb-3 pb-2 border-b border-brand-100 sticky top-0 bg-white/90 backdrop-blur-sm z-10">Observações</h3>
                <div class="mb-6">
                    <textarea name="observation" rows="3" class="input-pastel w-full hidpi:text-base">{{ old('observation', $anamnesis->observation ?? '') }}</textarea>
                </div>

                <h3 class="text-base hidpi:text-lg font-semibold text-brand-800 mb-3 pb-2 border-b border-brand-100 sticky top-0 bg-white/90 backdrop-blur-sm z-10">Termo de Consentimento</h3>
                <div class="mb-6 p-4 hidpi:p-6 sm:p-3 lg:p-4 rounded-lg border border-brand-100 bg-brand-50/30">
                    <p class="text-xs sm:text-xs lg:text-sm hidpi:text-base text-stone-600 mb-3 leading-relaxed">
                        Declaro que as informações acima são verdadeiras e autorizo a realização dos procedimentos estéticos,
                        assumindo total responsabilidade pelos dados informados. Estou ciente dos riscos e benefícios
                        dos procedimentos aos quais serei submetido(a).
                    </p>
                    <label class="flex items-start gap-3 hidpi:gap-4 cursor-pointer">
                        <input type="checkbox" name="consent" value="1" class="mt-1 w-5 h-5 hidpi:w-6 hidpi:h-6 sm:w-4 sm:h-4 text-brand-600 border-brand-300 rounded shrink-0" {{ old('consent', $anamnesis->consent ?? false) ? 'checked' : '' }}>
                        <span class="text-sm sm:text-xs lg:text-sm hidpi:text-base font-medium text-brand-700 leading-tight">Li e concordo com o termo acima</span>
                    </label>
                    @error('consent') <p class="text-rose-500 text-xs hidpi:text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 hidpi:gap-4">
                    <a href="{{ route('admin.anamnesis.index') }}" class="btn-pastel-secondary w-full sm:w-auto justify-center hidpi:text-base hidpi:py-2.5">Cancelar</a>
                    <button type="submit" class="btn-pastel-primary w-full sm:w-auto justify-center hidpi:text-base hidpi:py-2.5">
                        {{ isset($anamnesis) ? 'Atualizar' : 'Salvar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
