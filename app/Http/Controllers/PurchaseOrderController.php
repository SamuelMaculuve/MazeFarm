<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Services\AuditService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private readonly AuditService $audit,
        private readonly StockService $stock,
    ) {}

    public function index(Request $request)
    {
        $orders = PurchaseOrder::with('supplier')
            ->when($request->search, fn($q) => $q->where('po_number', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('purchases.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products  = Product::active()->orderBy('generic_name')->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        DB::transaction(function () use ($request) {
            $subtotal = collect($request->items)->sum(fn($i) => $i['quantity_ordered'] * $i['unit_price']);

            $order = PurchaseOrder::create([
                'po_number'   => PurchaseOrder::generatePoNumber(),
                'supplier_id' => $request->supplier_id,
                'status'      => 'draft',
                'order_date'  => $request->order_date,
                'expected_date'=> $request->expected_date,
                'subtotal'    => $subtotal,
                'total_amount'=> $subtotal,
                'notes'       => $request->notes,
                'created_by'  => Auth::id(),
                'updated_by'  => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id'        => $item['product_id'],
                    'quantity_ordered'  => $item['quantity_ordered'],
                    'quantity_received' => 0,
                    'unit_price'        => $item['unit_price'],
                    'subtotal'          => $item['quantity_ordered'] * $item['unit_price'],
                    'batch_number'      => $item['batch_number'] ?? null,
                    'expiry_date'       => $item['expiry_date'] ?? null,
                ]);
            }

            $this->audit->log('created', $order);
        });

        return redirect()->route('purchases.index')->with('success', __('common.saved'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product', 'createdBy']);
        $order = $purchaseOrder;
        return view('purchases.show', compact('order'));
    }

    public function submit(PurchaseOrder $purchaseOrder)
    {
        abort_if($purchaseOrder->status !== 'draft', 422, 'Only draft orders can be submitted.');
        $purchaseOrder->update(['status' => 'ordered', 'updated_by' => Auth::id()]);
        $this->audit->log('purchase_submitted', $purchaseOrder);
        return redirect()->route('purchases.show', $purchaseOrder)->with('success', __('purchases.order_submitted'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        // items array is keyed by item id: items[{id}][qty], items[{id}][batch_number], items[{id}][expires_at]
        $request->validate(['items' => 'required|array']);

        DB::transaction(function () use ($request, $purchaseOrder) {
            foreach ($request->items as $itemId => $data) {
                $item = PurchaseOrderItem::find($itemId);
                if (!$item || $item->purchase_order_id !== $purchaseOrder->id) continue;

                $qty = (int) ($data['qty'] ?? 0);
                if ($qty <= 0) continue;

                $item->increment('quantity_received', $qty);

                $batch = ProductBatch::create([
                    'product_id'        => $item->product_id,
                    'branch_id'         => $purchaseOrder->branch_id ?? auth()->user()->branch_id,
                    'supplier_id'       => $purchaseOrder->supplier_id,
                    'purchase_order_id' => $purchaseOrder->id,
                    'batch_number'      => $data['batch_number'] ?? ('LOTE-' . now()->format('Ym')),
                    'expiry_date'       => $data['expires_at'] ?? now()->addYears(2)->toDateString(),
                    'quantity_received' => $qty,
                    'quantity_current'  => $qty,
                    'purchase_price'    => $item->unit_price,
                    'sale_price'        => $item->product->sale_price,
                    'status'            => 'available',
                    'created_by'        => Auth::id(),
                ]);

                $this->stock->addStock($batch, $qty, 'purchase_order', $purchaseOrder->id);
            }

            $allReceived = $purchaseOrder->items()->whereColumn('quantity_received', '<', 'quantity_ordered')->doesntExist();
            $purchaseOrder->update([
                'status'        => $allReceived ? 'received' : 'partial',
                'received_date' => now(),
                'updated_by'    => Auth::id(),
            ]);

            $this->audit->log('purchase_received', $purchaseOrder);
        });

        return redirect()->route('purchases.show', $purchaseOrder)->with('success', __('purchases.received_success'));
    }
}
