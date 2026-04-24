<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\AuditService;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleService  $saleService,
        private readonly AuditService $audit,
    ) {}

    public function index(Request $request)
    {
        $sales = Sale::with(['customer', 'cashier', 'items'])
            ->when($request->search, fn($q) => $q->where('sale_number', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pos.sales', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'insuranceCard.insuranceCompany', 'items.product', 'payments', 'claims', 'cashier']);
        return view('pos.receipt', compact('sale'));
    }

    public function printReceipt(Sale $sale)
    {
        $sale->load(['customer', 'insuranceCard.insuranceCompany', 'items.product', 'payments', 'cashier']);
        return view('pos.receipt-print', compact('sale'));
    }

    public function cancel(Request $request, Sale $sale)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        if ($sale->status !== 'completed') {
            return back()->with('error', __('pos.cannot_cancel'));
        }

        $this->saleService->cancelSale($sale, $request->reason);

        return redirect()->route('pos.sales')->with('success', __('pos.sale_cancelled'));
    }
}
