<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(private readonly AuditService $audit) {}

    /**
     * Deduct stock using FEFO (First Expired, First Out).
     * Returns array of [batch_id => quantity_used].
     */
    public function deductStock(Product $product, int $quantity, string $referenceType, int $referenceId): array
    {
        $batches = ProductBatch::where('product_id', $product->id)
            ->where('status', 'available')
            ->where('quantity_current', '>', 0)
            ->orderBy('expiry_date')
            ->lockForUpdate()
            ->get();

        $totalAvailable = $batches->sum('quantity_current');

        if ($totalAvailable < $quantity) {
            throw new \RuntimeException(
                "Stock insuficiente para {$product->generic_name}. Disponível: {$totalAvailable}, Pedido: {$quantity}"
            );
        }

        $deductions = [];
        $remaining  = $quantity;

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $use              = min($remaining, $batch->quantity_current);
            $quantityBefore   = $batch->quantity_current;
            $batch->quantity_current -= $use;

            if ($batch->quantity_current === 0) {
                $batch->status = 'depleted';
            }
            $batch->save();

            StockMovement::create([
                'product_id'       => $product->id,
                'product_batch_id' => $batch->id,
                'branch_id'        => $batch->branch_id,
                'movement_type'    => $referenceType === 'sale' ? 'sale' : 'exit',
                'quantity'         => -$use,
                'quantity_before'  => $quantityBefore,
                'quantity_after'   => $batch->quantity_current,
                'reference_type'   => $referenceType,
                'reference_id'     => $referenceId,
                'created_by'       => Auth::id(),
            ]);

            $deductions[$batch->id] = $use;
            $remaining -= $use;
        }

        return $deductions;
    }

    public function addStock(ProductBatch $batch, int $quantity, string $referenceType, int $referenceId, ?string $notes = null): void
    {
        $before = $batch->quantity_current;
        $batch->increment('quantity_current', $quantity);
        if ($batch->status === 'depleted') {
            $batch->update(['status' => 'available']);
        }

        StockMovement::create([
            'product_id'       => $batch->product_id,
            'product_batch_id' => $batch->id,
            'branch_id'        => $batch->branch_id,
            'movement_type'    => 'entry',
            'quantity'         => $quantity,
            'quantity_before'  => $before,
            'quantity_after'   => $before + $quantity,
            'reference_type'   => $referenceType,
            'reference_id'     => $referenceId,
            'notes'            => $notes,
            'created_by'       => Auth::id(),
        ]);
    }

    public function adjust(ProductBatch $batch, int $newQuantity, string $reason): void
    {
        $before = $batch->quantity_current;
        $diff   = $newQuantity - $before;

        $batch->update([
            'quantity_current' => $newQuantity,
            'status'           => $newQuantity > 0 ? 'available' : 'depleted',
        ]);

        StockMovement::create([
            'product_id'       => $batch->product_id,
            'product_batch_id' => $batch->id,
            'branch_id'        => $batch->branch_id,
            'movement_type'    => 'adjustment',
            'quantity'         => $diff,
            'quantity_before'  => $before,
            'quantity_after'   => $newQuantity,
            'notes'            => $reason,
            'created_by'       => Auth::id(),
        ]);

        $this->audit->log('stock_adjusted', $batch, ['quantity' => $before], ['quantity' => $newQuantity], $reason);
    }

    public function markExpiredBatches(): int
    {
        return ProductBatch::where('status', 'available')
            ->where('expiry_date', '<', today())
            ->update(['status' => 'expired']);
    }
}
