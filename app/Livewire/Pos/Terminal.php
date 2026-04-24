<?php

namespace App\Livewire\Pos;

use App\Models\Customer;
use App\Models\InsuranceCard;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Terminal extends Component
{
    // Search
    public string $productSearch   = '';
    public string $customerSearch  = '';

    // Cart
    public array $cart = [];

    // Customer & insurance
    public ?int $customerId       = null;
    public ?int $insuranceCardId  = null;
    public string $authCode       = '';

    // Prescription
    public bool   $hasPrescription     = false;
    public string $prescriptionNumber  = '';
    public string $prescriptionDoctor  = '';

    // Payment
    public array  $payments = [];
    public string $notes    = '';

    // UI state
    public bool $showPaymentStep    = false;
    public bool $saleCompleted      = false;
    public ?int $lastSaleId         = null;
    public string $errorMessage     = '';

    public function updatedProductSearch(): void
    {
        // Triggers re-render; results fetched via computed property
    }

    #[Computed]
    public function productResults(): \Illuminate\Database\Eloquent\Collection
    {
        if (strlen($this->productSearch) < 2) {
            return collect();
        }

        return Product::active()
            ->search($this->productSearch)
            ->with('category')
            ->limit(8)
            ->get()
            ->each(function ($p) {
                $p->setAttribute('stock', $p->total_stock);
            });
    }

    #[Computed]
    public function customerResults(): \Illuminate\Database\Eloquent\Collection
    {
        if (strlen($this->customerSearch) < 2) {
            return collect();
        }

        return Customer::active()
            ->search($this->customerSearch)
            ->with('activeInsuranceCards.insuranceCompany')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function selectedCustomer(): ?Customer
    {
        return $this->customerId ? Customer::with('activeInsuranceCards.insuranceCompany')->find($this->customerId) : null;
    }

    #[Computed]
    public function selectedInsuranceCard(): ?InsuranceCard
    {
        return $this->insuranceCardId ? InsuranceCard::with('insuranceCompany')->find($this->insuranceCardId) : null;
    }

    public function addProduct(int $productId): void
    {
        $product = Product::find($productId);
        if (!$product) return;

        $existing = collect($this->cart)->search(fn($i) => $i['product_id'] === $productId);

        if ($existing !== false) {
            $this->cart[$existing]['quantity']++;
            $this->recalculateItem($existing);
        } else {
            $this->cart[] = [
                'product_id'           => $product->id,
                'name'                 => $product->generic_name . ($product->commercial_name ? " ({$product->commercial_name})" : ''),
                'requires_prescription'=> $product->requires_prescription,
                'is_controlled'        => $product->is_controlled,
                'quantity'             => 1,
                'unit_price'           => (float) $product->sale_price,
                'discount_pct'         => 0,
                'insurance_coverage_pct' => $this->getInsuranceCoverage($product),
                'stock'                => $product->total_stock,
            ];
            $this->recalculateItem(count($this->cart) - 1);
        }

        $this->productSearch = '';
        $this->errorMessage  = '';
    }

    public function removeFromCart(int $index): void
    {
        array_splice($this->cart, $index, 1);
    }

    public function updateQuantity(int $index, int $qty): void
    {
        if ($qty < 1) return;

        $stock = $this->cart[$index]['stock'] ?? 0;
        if ($qty > $stock) {
            $this->errorMessage = __('pos.insufficient_stock', ['name' => $this->cart[$index]['name'], 'available' => $stock]);
            return;
        }

        $this->cart[$index]['quantity'] = $qty;
        $this->recalculateItem($index);
        $this->errorMessage = '';
    }

    public function selectCustomer(int $customerId): void
    {
        $this->customerId      = $customerId;
        $this->customerSearch  = '';
        $this->insuranceCardId = null;

        // Recalculate insurance coverage for all items
        $this->refreshInsuranceCoverage();
    }

    public function clearCustomer(): void
    {
        $this->customerId      = null;
        $this->insuranceCardId = null;
        $this->customerSearch  = '';
        $this->refreshInsuranceCoverage();
    }

    public function selectInsuranceCard(int $cardId): void
    {
        $this->insuranceCardId = $cardId;
        $this->refreshInsuranceCoverage();
    }

    public function clearInsuranceCard(): void
    {
        $this->insuranceCardId = null;
        $this->refreshInsuranceCoverage();
    }

    public function proceedToPayment(): void
    {
        if (empty($this->cart)) {
            $this->errorMessage = __('pos.cart_empty');
            return;
        }

        $needsPrescription = collect($this->cart)->contains('requires_prescription', true);
        if ($needsPrescription && !$this->hasPrescription) {
            $this->errorMessage = __('pos.prescription_required');
            return;
        }

        $this->payments = [
            ['method' => 'cash', 'amount' => $this->cartCustomerTotal, 'reference' => ''],
        ];

        $this->showPaymentStep = true;
    }

    public function addPaymentLine(): void
    {
        $this->payments[] = ['method' => 'cash', 'amount' => 0, 'reference' => ''];
    }

    public function removePaymentLine(int $index): void
    {
        if (count($this->payments) > 1) {
            array_splice($this->payments, $index, 1);
        }
    }

    public function completeSale(): void
    {
        $this->errorMessage = '';

        $paidTotal = collect($this->payments)->sum('amount');
        if (abs($paidTotal - $this->cartCustomerTotal) > 0.01) {
            $this->errorMessage = __('pos.payment_mismatch', ['expected' => number_format($this->cartCustomerTotal, 2), 'paid' => number_format($paidTotal, 2)]);
            return;
        }

        try {
            $saleService = app(SaleService::class);

            $items = collect($this->cart)->map(fn($i) => [
                'product_id'             => $i['product_id'],
                'quantity'               => $i['quantity'],
                'unit_price'             => $i['unit_price'],
                'discount_pct'           => $i['discount_pct'],
                'insurance_coverage_pct' => $i['insurance_coverage_pct'],
                'prescription_validated' => $this->hasPrescription,
            ])->toArray();

            $sale = $saleService->completeSale(
                [
                    'customer_id'         => $this->customerId,
                    'insurance_card_id'   => $this->insuranceCardId,
                    'has_prescription'    => $this->hasPrescription,
                    'prescription_number' => $this->prescriptionNumber,
                    'prescription_doctor' => $this->prescriptionDoctor,
                    'insurance_auth_code' => $this->authCode,
                    'notes'               => $this->notes,
                ],
                $items,
                collect($this->payments)->filter(fn($p) => $p['amount'] > 0)->values()->map(fn($p) => [
                    'method'    => $p['method'],
                    'amount'    => $p['amount'],
                    'reference' => $p['reference'] ?? null,
                ])->toArray()
            );

            $this->lastSaleId   = $sale->id;
            $this->saleCompleted = true;
            $this->resetTerminal();

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function resetTerminal(): void
    {
        $this->cart              = [];
        $this->customerId        = null;
        $this->insuranceCardId   = null;
        $this->authCode          = '';
        $this->hasPrescription   = false;
        $this->prescriptionNumber= '';
        $this->prescriptionDoctor= '';
        $this->payments          = [];
        $this->notes             = '';
        $this->showPaymentStep   = false;
        $this->productSearch     = '';
        $this->customerSearch    = '';
        $this->errorMessage      = '';
    }

    public function newSale(): void
    {
        $this->saleCompleted = false;
        $this->lastSaleId    = null;
        $this->resetTerminal();
    }

    // ─── Computed totals ──────────────────────────────────────────────────

    #[Computed]
    public function cartTotal(): float
    {
        return round(collect($this->cart)->sum(fn($i) => ($i['unit_price'] * $i['quantity']) * (1 - $i['discount_pct'] / 100)), 2);
    }

    #[Computed]
    public function cartInsuranceTotal(): float
    {
        return round(collect($this->cart)->sum(fn($i) => ($i['unit_price'] * $i['quantity']) * (1 - $i['discount_pct'] / 100) * ($i['insurance_coverage_pct'] / 100)), 2);
    }

    #[Computed]
    public function cartCustomerTotal(): float
    {
        return round($this->cartTotal - $this->cartInsuranceTotal, 2);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    private function recalculateItem(int $index): void
    {
        $item  = &$this->cart[$index];
        $net   = $item['unit_price'] * $item['quantity'] * (1 - $item['discount_pct'] / 100);
        $ins   = $net * $item['insurance_coverage_pct'] / 100;
        $item['subtotal']          = round($net, 2);
        $item['insurance_amount']  = round($ins, 2);
        $item['customer_amount']   = round($net - $ins, 2);
    }

    private function getInsuranceCoverage(Product $product): float
    {
        if (!$this->insuranceCardId) return 0;

        $card = InsuranceCard::with('insuranceCompany')->find($this->insuranceCardId);
        if (!$card || !$card->is_valid) return 0;

        return $card->insuranceCompany->getCoverageForProduct($product);
    }

    private function refreshInsuranceCoverage(): void
    {
        foreach ($this->cart as $index => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $this->cart[$index]['insurance_coverage_pct'] = $this->getInsuranceCoverage($product);
                $this->recalculateItem($index);
            }
        }
    }

    public function render()
    {
        return view('livewire.pos.terminal');
    }
}
