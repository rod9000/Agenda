@extends('layouts.app')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Ficha de Anamnese</h2>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.anamnesis.edit', $anamnesis) }}" class="btn-pastel-primary flex-1 sm:flex-none justify-center">Editar</a>
            <a href="{{ route('admin.anamnesis.index') }}" class="btn-pastel-secondary flex-1 sm:flex-none justify-center">Voltar</a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-6">
            <div class="text-center mb-6">
                <h1 class="text-xl hidpi:text-2xl font-bold text-brand-800">FICHA DE ANAMNESE</h1>
                <p class="text-sm hidpi:text-base text-brand-400">{{ $anamnesis->answered_at?->format('d/m/Y H:i') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 hidpi:gap-5 p-4 hidpi:p-5 rounded-lg bg-brand-50/50 mb-6">
                <div>
                    <span class="text-xs hidpi:text-sm font-medium text-brand-400">CLIENTE</span>
                    <p class="font-semibold text-stone-800 text-sm sm:text-base hidpi:text-lg">{{ $anamnesis->customer?->name }}</p>
                </div>
                <div>
                    <span class="text-xs hidpi:text-sm font-medium text-brand-400">CPF</span>
                    <p class="font-semibold text-stone-800 text-sm sm:text-base hidpi:text-lg">{{ $anamnesis->customer?->cpf }}</p>
                </div>
                <div>
                    <span class="text-xs hidpi:text-sm font-medium text-brand-400">TELEFONE</span>
                    <p class="font-semibold text-stone-800 text-sm sm:text-base hidpi:text-lg">{{ $anamnesis->customer?->phone }}</p>
                </div>
                <div>
                    <span class="text-xs hidpi:text-sm font-medium text-brand-400">PREECHIDO POR</span>
                    <p class="font-semibold text-stone-800 text-sm sm:text-base hidpi:text-lg">{{ $anamnesis->answeredBy?->name ?? '—' }}</p>
                </div>
            </div>

            <div class="space-y-6 hidpi:space-y-8">
                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-2 pb-1 border-b border-brand-100">Saúde Geral</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 hidpi:gap-3">
                        @foreach([
                            'heart_problem' => 'Problema cardíaco',
                            'high_pressure' => 'Pressão alta',
                            'low_pressure' => 'Pressão baixa',
                            'diabetes' => 'Diabetes',
                            'epilepsy' => 'Epilepsia',
                            'cancer' => 'Câncer',
                            'autoimmune' => 'Doença autoimune',
                            'kidney_disease' => 'Doença renal',
                            'hepatitis' => 'Hepatite',
                            'hiv' => 'HIV',
                            'pregnant' => 'Gestante/lactante',
                        ] as $field => $label)
                            <div class="flex items-center gap-2 hidpi:gap-3 text-sm hidpi:text-base">
                                <span class="inline-block w-4 h-4 hidpi:w-5 hidpi:h-5 shrink-0 rounded-full {{ $anamnesis->$field ? 'bg-red-400' : 'bg-emerald-400' }}"></span>
                                <span class="{{ $anamnesis->$field ? 'text-red-700 font-medium' : 'text-stone-500' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-2 pb-1 border-b border-brand-100">Pele e Procedimentos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 hidpi:gap-3">
                        @foreach([
                            'skin_disease' => 'Doença de pele',
                            'keloids' => 'Queloide',
                            'isotretinoin' => 'Roacutan/Isotretinoína',
                            'cosmetic_procedure' => 'Procedimento estético recente',
                        ] as $field => $label)
                            <div class="flex items-center gap-2 hidpi:gap-3 text-sm hidpi:text-base">
                                <span class="inline-block w-4 h-4 hidpi:w-5 hidpi:h-5 shrink-0 rounded-full {{ $anamnesis->$field ? 'bg-red-400' : 'bg-emerald-400' }}"></span>
                                <span class="{{ $anamnesis->$field ? 'text-red-700 font-medium' : 'text-stone-500' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-2 pb-1 border-b border-brand-100">Cirurgias e Implantes</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 hidpi:gap-3">
                        @foreach([
                            'recent_surgery' => 'Cirurgia recente',
                            'pacemaker' => 'Marcapasso',
                            'dental_implants' => 'Implante dentário',
                        ] as $field => $label)
                            <div class="flex items-center gap-2 hidpi:gap-3 text-sm hidpi:text-base">
                                <span class="inline-block w-4 h-4 hidpi:w-5 hidpi:h-5 shrink-0 rounded-full {{ $anamnesis->$field ? 'bg-red-400' : 'bg-emerald-400' }}"></span>
                                <span class="{{ $anamnesis->$field ? 'text-red-700 font-medium' : 'text-stone-500' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-2 pb-1 border-b border-brand-100">Medicamentos e Tratamentos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 hidpi:gap-3">
                        @foreach([
                            'allergies' => 'Alergias',
                            'medications' => 'Uso de medicamentos',
                            'medical_treatment' => 'Tratamento médico',
                            'topical_medication' => 'Medicamento tópico',
                        ] as $field => $label)
                            <div class="flex items-center gap-2 hidpi:gap-3 text-sm hidpi:text-base">
                                <span class="inline-block w-4 h-4 hidpi:w-5 hidpi:h-5 shrink-0 rounded-full {{ $anamnesis->$field ? 'bg-red-400' : 'bg-emerald-400' }}"></span>
                                <span class="{{ $anamnesis->$field ? 'text-red-700 font-medium' : 'text-stone-500' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($anamnesis->allergy_description)
                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-1">Descrição de Alergias</h3>
                    <p class="text-sm hidpi:text-base text-stone-600 p-3 hidpi:p-4 rounded bg-brand-50/50">{{ $anamnesis->allergy_description }}</p>
                </div>
                @endif

                @if($anamnesis->medication_description)
                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-1">Descrição de Medicamentos</h3>
                    <p class="text-sm hidpi:text-base text-stone-600 p-3 hidpi:p-4 rounded bg-brand-50/50">{{ $anamnesis->medication_description }}</p>
                </div>
                @endif

                @if($anamnesis->medical_treatment_description)
                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-1">Descrição de Tratamento</h3>
                    <p class="text-sm hidpi:text-base text-stone-600 p-3 hidpi:p-4 rounded bg-brand-50/50">{{ $anamnesis->medical_treatment_description }}</p>
                </div>
                @endif

                @if($anamnesis->observation)
                <div>
                    <h3 class="text-sm hidpi:text-base font-semibold text-brand-700 mb-1">Observações</h3>
                    <p class="text-sm hidpi:text-base text-stone-600 p-3 hidpi:p-4 rounded bg-brand-50/50">{{ $anamnesis->observation }}</p>
                </div>
                @endif

                <div class="p-4 hidpi:p-5 rounded-lg border border-brand-100 text-center">
                    <p class="text-sm hidpi:text-base font-medium text-brand-700">
                        Consentimento: 
                        @if($anamnesis->consent)
                            <span class="text-emerald-600">✓ Assinado em {{ $anamnesis->answered_at?->format('d/m/Y H:i') }}</span>
                        @else
                            <span class="text-amber-600">Pendente</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
