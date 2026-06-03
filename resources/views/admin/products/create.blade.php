@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">{{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}</h2>
        <a href="{{ route('admin.products.index') }}" class="btn-pastel-secondary">Voltar</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel max-w-lg">
            <form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}">
                @csrf
                @if(isset($product)) @method('PUT') @endif

                <div class="mb-4">
                    <label class="label">Nome</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}" class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Validade</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $product->expiry_date?->format('Y-m-d') ?? '') }}" class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Preço de Compra</label>
                    <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" required class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Preço de Venda</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Quantidade em Estoque</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" min="0" class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Estoque Mínimo (alerta)</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 0) }}" min="0" class="input-pastel">
                </div>

                <div class="mb-4">
                    <label class="label">Fornecedor</label>
                    <input type="text" name="supplier" value="{{ old('supplier', $product->supplier ?? '') }}" class="input-pastel">
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn-pastel-secondary">Cancelar</a>
                    <button type="submit" class="btn-pastel-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
