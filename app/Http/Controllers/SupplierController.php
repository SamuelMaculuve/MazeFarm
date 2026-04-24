<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request)
    {
        $suppliers = Supplier::when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('purchases.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('purchases.suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create(array_merge($request->validated(), ['created_by' => Auth::id()]));
        $this->audit->log('created', $supplier);

        return redirect()->route('suppliers.index')->with('success', __('common.saved'));
    }

    public function edit(Supplier $supplier)
    {
        return view('purchases.suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        $this->audit->log('updated', $supplier);

        return redirect()->route('suppliers.index')->with('success', __('common.updated'));
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', __('common.deleted'));
    }
}
