@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-brand-800 leading-tight">{{ isset($customer) ? 'Editar Cliente' : 'Novo Cliente' }}</h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel">
            <form method="POST" action="{{ isset($customer) ? route('admin.customers.update', $customer) : route('admin.customers.store') }}">
                @csrf
                @if(isset($customer)) @method('PUT') @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required class="input-pastel">
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf', $customer->cpf ?? '') }}" required maxlength="14" class="input-pastel">
                    @error('cpf') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">Telefone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required maxlength="20" class="input-pastel">
                    @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">Data de Nascimento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', isset($customer) ? $customer->birth_date->format('Y-m-d') : '') }}" required class="input-pastel">
                    @error('birth_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" class="input-pastel">
                    @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">Observações</label>
                    <textarea name="notes" rows="3" class="input-pastel">{{ old('notes', $customer->notes ?? '') }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn-pastel-secondary">Cancelar</a>
                    <button type="submit" class="btn-pastel-primary">
                        {{ isset($customer) ? 'Atualizar' : 'Salvar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
