<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\StockMovement;
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

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['quantity'] = $data['quantity'] ?? 0;

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show(Product $product)
    {
        $product->load('stockMovements.user');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->stockMovements()->delete();
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Produto excluído com sucesso!');
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->latest();

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($productId = $request->get('product_id')) {
            $query->where('product_id', $productId);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $movements = $query->paginate(30);
        $products = Product::orderBy('name')->get();

        return view('admin.products.movements', compact('movements', 'products'));
    }

    public function stockReport(Request $request)
    {
        $query = Product::query();

        $filter = $request->get('filter', 'all');
        $supplier = $request->get('supplier');

        if ($filter === 'low_stock') {
            $query->whereRaw('quantity > 0 AND quantity <= min_stock');
        } elseif ($filter === 'out_of_stock') {
            $query->where('quantity', '<=', 0);
        } elseif ($filter === 'expiring') {
            $query->whereNotNull('expiry_date')
                  ->whereBetween('expiry_date', [now(), now()->addDays(30)]);
        }

        if ($supplier) {
            $query->where('supplier', $supplier);
        }

        $products = $query->orderBy('name')->get();

        $totalProducts = Product::count();
        $lowStockCount = Product::whereRaw('quantity > 0 AND quantity <= min_stock')->count();
        $outOfStockCount = Product::where('quantity', '<=', 0)->count();
        $totalStockValue = Product::sum(\DB::raw('quantity * purchase_price'));
        $expiringCount = Product::whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->count();

        $suppliers = Product::whereNotNull('supplier')->distinct()->orderBy('supplier')->pluck('supplier');

        return view('admin.products.stock-report', compact(
            'products', 'totalProducts', 'lowStockCount', 'outOfStockCount',
            'totalStockValue', 'expiringCount', 'suppliers', 'filter', 'supplier'
        ));
    }

    public function movementStore(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($data['type'] === 'in') {
            $product->addStock($data['quantity'], $data['notes']);
        } else {
            $movement = $product->removeStock($data['quantity'], $data['notes']);
            if (!$movement) {
                return redirect()->back()->with('error', 'Estoque insuficiente!');
            }
        }

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Movimentação registrada com sucesso!');
    }
}
