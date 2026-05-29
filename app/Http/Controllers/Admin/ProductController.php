<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'brand'          => 'nullable|string|max:100',
            'expiry_date'    => 'nullable|date',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'brand'          => 'nullable|string|max:100',
            'expiry_date'    => 'nullable|date',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}
