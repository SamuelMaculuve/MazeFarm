<?php

namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use App\Models\InsuranceCompany;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $from = $request->from ?? today()->startOfMonth()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $baseQuery = Sale::where('status', 'completed')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);

        $totals = (clone $baseQuery)
            ->selectRaw('COUNT(*) as count, SUM(total_amount) as total_sales, AVG(total_amount) as avg_ticket, SUM(insurance_amount) as insurance_total')
            ->first();

        $summary = [
            'total_sales'     => $totals->total_sales ?? 0,
            'count'           => $totals->count ?? 0,
            'avg_ticket'      => $totals->avg_ticket ?? 0,
            'insurance_total' => $totals->insurance_total ?? 0,
        ];

        $byPayment = DB::table('sale_payments')
            ->join('sales', 'sales.id', '=', 'sale_payments.sale_id')
            ->where('sales.status', 'completed')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$from, $to])
            ->when($request->payment_method, fn($q) => $q->where('sale_payments.payment_method', $request->payment_method))
            ->select('sale_payments.payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(sale_payments.amount) as total'))
            ->groupBy('sale_payments.payment_method')
            ->orderByDesc('total')
            ->get();

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->where('sales.status', 'completed')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$from, $to])
            ->select('products.generic_name as product_name', DB::raw('SUM(sale_items.quantity) as qty_sold'), DB::raw('SUM(sale_items.subtotal) as revenue'))
            ->groupBy('products.id', 'products.generic_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return view('reports.sales', compact('summary', 'byPayment', 'topProducts', 'from', 'to'));
    }

    public function stock(Request $request)
    {
        $categories = ProductCategory::orderBy('name')->get();

        $productsQuery = Product::with(['category', 'activeBatches'])
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->alert === 'low', fn($q) => $q->lowStock())
            ->when($request->alert === 'expired', function ($q) {
                $q->whereHas('batches', fn($bq) => $bq->where('status', 'expired'));
            })
            ->when($request->alert === 'expiring', function ($q) {
                $q->whereHas('batches', fn($bq) => $bq->where('status', 'available')->where('expiry_date', '<=', now()->addDays(30)));
            })
            ->orderBy('generic_name');

        $products = $productsQuery->paginate(30)->withQueryString();

        $summary = [
            'total_products'  => Product::active()->count(),
            'low_stock_count' => Product::active()->lowStock()->count(),
            'expiring_count'  => Product::active()->whereHas('batches', fn($q) => $q->where('status', 'available')->where('expiry_date', '<=', now()->addDays(30)))->count(),
            'total_value'     => DB::table('products')
                ->join('product_batches', 'product_batches.product_id', '=', 'products.id')
                ->where('products.is_active', true)
                ->where('product_batches.status', 'available')
                ->sum(DB::raw('product_batches.quantity_current * products.purchase_price')),
        ];

        return view('reports.stock', compact('products', 'categories', 'summary'));
    }

    public function insurance(Request $request)
    {
        $from      = $request->from ?? today()->startOfMonth()->toDateString();
        $to        = $request->to   ?? today()->toDateString();
        $companies = InsuranceCompany::orderBy('name')->get();

        $baseQuery = InsuranceClaim::with(['insuranceCompany', 'insuranceCard.customer'])
            ->whereBetween(DB::raw('DATE(insurance_claims.created_at)'), [$from, $to])
            ->when($request->company, fn($q) => $q->where('insurance_claims.insurance_company_id', $request->company))
            ->when($request->status, fn($q) => $q->where('insurance_claims.status', $request->status));

        $claims = (clone $baseQuery)->latest()->paginate(20)->withQueryString();

        $totals = (clone $baseQuery)->selectRaw(
            'SUM(insurance_claims.amount_claimed) as total_claimed, SUM(insurance_claims.amount_approved) as total_approved, SUM(insurance_claims.amount_paid) as total_paid, SUM(insurance_claims.status="pending") as pending_count'
        )->first();

        $summary = [
            'total_claimed'  => $totals->total_claimed ?? 0,
            'total_approved' => $totals->total_approved ?? 0,
            'total_paid'     => $totals->total_paid ?? 0,
            'pending_count'  => $totals->pending_count ?? 0,
        ];

        $byCompany = (clone $baseQuery)
            ->join('insurance_companies', 'insurance_companies.id', '=', 'insurance_claims.insurance_company_id')
            ->select(
                'insurance_companies.name as company_name',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount_claimed) as total_claimed'),
                DB::raw('SUM(amount_approved) as total_approved'),
                DB::raw('SUM(amount_paid) as total_paid')
            )
            ->groupBy('insurance_claims.insurance_company_id', 'insurance_companies.name')
            ->get();

        return view('reports.insurance', compact('claims', 'summary', 'byCompany', 'companies', 'from', 'to'));
    }
}
