@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-amber-800 leading-tight">{{ isset($service) ? 'Editar Procedimento' : 'Novo Procedimento' }}</h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel">
            <form method="POST" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}">
                @csrf
                @if(isset($service)) @method('PUT') @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-amber-700">Nome do Procedimento</label>
                    <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required class="input-pastel">
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-amber-700">Duração (minutos)</label>
                        <input type="number" name="duration_min" value="{{ old('duration_min', $service->duration_min ?? '') }}" required min="15" class="input-pastel">
                        @error('duration_min') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-amber-700">Valor (R$)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $service->price ?? '') }}" required min="0" class="input-pastel">
                        @error('price') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-amber-700">Cor do Calendário</label>
                    <input type="color" name="color_hex" value="{{ old('color_hex', $service->color_hex ?? '#fbbf24') }}" class="mt-1 h-10 w-20 rounded-lg border-amber-200 shadow-sm cursor-pointer">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-amber-700">Estimativa do valor usado do produto (R$)</label>
                    <input type="number" step="0.01" name="estimated_product_cost" value="{{ old('estimated_product_cost', $service->estimated_product_cost ?? '') }}" min="0" class="input-pastel">
                    @error('estimated_product_cost') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-amber-700">Descrição</label>
                    <textarea name="description" rows="3" class="input-pastel">{{ old('description', $service->description ?? '') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="active" value="1" {{ old('active', $service->active ?? true) ? 'checked' : '' }} class="rounded border-amber-300 text-amber-600 shadow-sm focus:ring-amber-300">
                        <span class="text-sm text-amber-700">Ativo</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.services.index') }}" class="btn-pastel-secondary">Cancelar</a>
                    <button type="submit" class="btn-pastel-primary">
                        {{ isset($service) ? 'Atualizar' : 'Salvar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
