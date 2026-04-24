<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductCategory;
use App\Services\AuditService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private readonly AuditService  $audit,
        private readonly StockService  $stock,
    ) {}

    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->low_stock, fn($q) => $q->lowStock())
            ->orderBy('generic_name')
            ->paginate(20)
            ->withQueryString();

        $categories = ProductCategory::active()->orderBy('name')->get();

        return view('stock.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->orderBy('name')->get();
        return view('stock.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create(array_merge(
            $request->validated(),
            ['created_by' => Auth::id(), 'updated_by' => Auth::id()]
        ));

        $this->audit->log('created', $product, [], $product->toArray(), "Produto criado: {$product->generic_name}");

        return redirect()->route('stock.index')
            ->with('success', __('common.saved'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'batches' => fn($q) => $q->orderBy('expiry_date')]);

        $movements = $product->stockMovements()
            ->with('createdBy')
            ->latest()
            ->paginate(15);

        return view('stock.show', compact('product', 'movements'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::active()->orderBy('name')->get();
        return view('stock.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $old = $product->toArray();
        $product->update(array_merge($request->validated(), ['updated_by' => Auth::id()]));

        $this->audit->log('updated', $product, $old, $product->toArray());

        return redirect()->route('stock.show', $product)
            ->with('success', __('common.updated'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->audit->log('deleted', $product, $product->toArray());

        return redirect()->route('stock.index')
            ->with('success', __('common.deleted'));
    }

    public function adjustStock(Request $request, ProductBatch $batch)
    {
        $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason'       => 'required|string|max:255',
        ]);

        $this->stock->adjust($batch, $request->new_quantity, $request->reason);

        return back()->with('success', __('stock.adjustment_saved'));
    }
}
