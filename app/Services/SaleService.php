<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\InsuranceCard;
use App\Models\InsuranceClaim;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        private readonly StockService $stock,
        private readonly AuditService $audit,
    ) {}

    /**
     * Complete a sale.
     *
     * $items: array of [
     *   'product_id'       => int,
     *   'quantity'         => int,
     *   'unit_price'       => float,
     *   'discount_pct'     => float,  // 0–100
     *   'insurance_coverage_pct' => float,  // calculated by caller
     * ]
     *
     * $payments: array of [
     *   'method'    => string,
     *   'amount'    => float,
     *   'reference' => string|null,
     * ]
     */
    public function completeSale(array $saleData, array $items, array $payments): Sale
    {
        return DB::transaction(function () use ($saleData, $items, $payments) {
            $sale = Sale::create(array_merge($saleData, [
                'sale_number' => Sale::generateSaleNumber(),
                'status'      => 'completed',
                'cashier_id'  => Auth::id(),
                'created_by'  => Auth::id(),
            ]));

            $subtotal         = 0;
            $totalInsurance   = 0;
            $totalCustomer    = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $lineSubtotal      = round($item['unit_price'] * $item['quantity'], 2);
                $discountAmount    = round($lineSubtotal * ($item['discount_pct'] ?? 0) / 100, 2);
                $lineNet           = $lineSubtotal - $discountAmount;
                $insurancePct      = $item['insurance_coverage_pct'] ?? 0;
                $insuranceAmount   = round($lineNet * $insurancePct / 100, 2);
                $customerAmount    = $lineNet - $insuranceAmount;

                // Deduct stock (FEFO) and get batch mapping
                $batchDeductions = $this->stock->deductStock(
                    $product,
                    $item['quantity'],
                    'sale',
                    $sale->id
                );
                $primaryBatchId = array_key_first($batchDeductions);

                SaleItem::create([
                    'sale_id'               => $sale->id,
                    'product_id'            => $product->id,
                    'product_batch_id'      => $primaryBatchId,
                    'quantity'              => $item['quantity'],
                    'unit_price'            => $item['unit_price'],
                    'discount_percentage'   => $item['discount_pct'] ?? 0,
                    'subtotal'              => $lineNet,
                    'insurance_coverage_pct'=> $insurancePct,
                    'insurance_amount'      => $insuranceAmount,
                    'customer_amount'       => $customerAmount,
                    'prescription_validated'=> $item['prescription_validated'] ?? false,
                ]);

                $subtotal       += $lineNet;
                $totalInsurance += $insuranceAmount;
                $totalCustomer  += $customerAmount;
            }

            $sale->update([
                'subtotal'         => $subtotal,
                'total_amount'     => $subtotal,
                'customer_amount'  => $totalCustomer,
                'insurance_amount' => $totalInsurance,
            ]);

            // Record payments
            foreach ($payments as $payment) {
                SalePayment::create([
                    'sale_id'          => $sale->id,
                    'payment_method'   => $payment['method'],
                    'amount'           => $payment['amount'],
                    'reference_number' => $payment['reference'] ?? null,
                    'status'           => 'completed',
                ]);
            }

            // Handle credit (fiado)
            $creditPayment = collect($payments)->firstWhere('method', 'credit');
            if ($creditPayment && $sale->customer_id) {
                $customer = Customer::find($sale->customer_id);
                $customer?->increment('credit_balance', $creditPayment['amount']);
            }

            // Create insurance claim if applicable
            if ($sale->insurance_card_id && $totalInsurance > 0) {
                $this->createInsuranceClaim($sale, $totalInsurance);
            }

            $this->audit->log('sale_completed', $sale, [], [], "Venda {$sale->sale_number} — {$sale->total_amount} MZN");

            return $sale->fresh(['items.product', 'payments', 'customer']);
        });
    }

    private function createInsuranceClaim(Sale $sale, float $amount): InsuranceClaim
    {
        $card = InsuranceCard::find($sale->insurance_card_id);

        $claim = InsuranceClaim::create([
            'claim_number'        => InsuranceClaim::generateClaimNumber(),
            'sale_id'             => $sale->id,
            'insurance_card_id'   => $card->id,
            'insurance_company_id'=> $card->insurance_company_id,
            'authorization_code'  => $sale->insurance_auth_code,
            'amount_claimed'      => $amount,
            'status'              => 'pending',
            'created_by'          => Auth::id(),
        ]);

        // Track coverage used
        $card->increment('coverage_used', $amount);

        return $claim;
    }

    public function cancelSale(Sale $sale, string $reason): void
    {
        DB::transaction(function () use ($sale, $reason) {
            // Restore stock
            foreach ($sale->items as $item) {
                if ($item->product_batch_id) {
                    $this->stock->addStock(
                        $item->batch,
                        $item->quantity,
                        'return',
                        $sale->id,
                        "Cancelamento venda {$sale->sale_number}"
                    );
                }
            }

            // Reverse credit if applicable
            $creditPayment = $sale->payments()->where('payment_method', 'credit')->first();
            if ($creditPayment && $sale->customer_id) {
                $customer = Customer::find($sale->customer_id);
                $customer?->decrement('credit_balance', $creditPayment->amount);
            }

            $sale->update(['status' => 'cancelled', 'notes' => $reason]);

            // Cancel insurance claim
            $sale->claims()->where('status', 'pending')->update(['status' => 'rejected', 'rejection_reason' => 'Venda cancelada']);

            $this->audit->log('sale_cancelled', $sale, [], ['reason' => $reason]);
        });
    }
}
