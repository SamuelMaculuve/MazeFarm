<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\InsuranceCard;
use App\Models\InsuranceCompany;
use App\Models\InsuranceCoverageRule;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::where('is_main', true)->first() ?? Branch::first();
        $admin  = \App\Models\User::first();

        // ── Suppliers ──────────────────────────────────────────────────────
        $suppliers = [
            ['name' => 'MedFarm Distribuidora Lda', 'nuit' => '400123456', 'phone' => '+258 21 300 100', 'email' => 'geral@medfarm.co.mz', 'contact_person' => 'Carlos Mutua'],
            ['name' => 'Pharma Import Moçambique', 'nuit' => '400234567', 'phone' => '+258 21 400 200', 'email' => 'vendas@pharmaimport.mz', 'contact_person' => 'Ana Sitoe'],
            ['name' => 'BioMed Fornecimentos', 'nuit' => '400345678', 'phone' => '+258 84 555 7890', 'email' => 'info@biomed.mz', 'contact_person' => 'João Nhantumbo'],
        ];

        $supplierModels = collect($suppliers)->map(fn($s) => Supplier::firstOrCreate(['name' => $s['name']], array_merge($s, ['is_active' => true])));

        // ── Products ───────────────────────────────────────────────────────
        $cats = ProductCategory::pluck('id', 'name');

        $products = [
            // Analgesics
            ['generic_name' => 'Paracetamol', 'commercial_name' => 'Panadol', 'category' => 'Analgésicos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '500mg', 'unit' => 'caixa', 'barcode' => '6009700120012', 'purchase_price' => 45.00, 'sale_price' => 75.00, 'minimum_stock' => 20, 'reorder_point' => 50],
            ['generic_name' => 'Ibuprofeno', 'commercial_name' => 'Brufen', 'category' => 'Analgésicos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '400mg', 'unit' => 'caixa', 'barcode' => '6009700120019', 'purchase_price' => 60.00, 'sale_price' => 95.00, 'minimum_stock' => 15, 'reorder_point' => 30],
            ['generic_name' => 'Diclofenac Sódico', 'commercial_name' => 'Voltaren', 'category' => 'Analgésicos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '50mg', 'unit' => 'caixa', 'barcode' => '6009700120026', 'purchase_price' => 85.00, 'sale_price' => 130.00, 'minimum_stock' => 10, 'reorder_point' => 25],

            // Antibiotics
            ['generic_name' => 'Amoxicilina', 'commercial_name' => 'Amoxil', 'category' => 'Antibióticos', 'pharmaceutical_form' => 'Cápsula', 'dosage' => '500mg', 'unit' => 'caixa', 'barcode' => '6009700120033', 'requires_prescription' => true, 'purchase_price' => 120.00, 'sale_price' => 185.00, 'minimum_stock' => 15, 'reorder_point' => 30],
            ['generic_name' => 'Ciprofloxacina', 'commercial_name' => 'Ciproxin', 'category' => 'Antibióticos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '500mg', 'unit' => 'caixa', 'barcode' => '6009700120040', 'requires_prescription' => true, 'purchase_price' => 180.00, 'sale_price' => 270.00, 'minimum_stock' => 10, 'reorder_point' => 20],
            ['generic_name' => 'Metronidazol', 'commercial_name' => 'Flagyl', 'category' => 'Antibióticos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '250mg', 'unit' => 'caixa', 'barcode' => '6009700120057', 'requires_prescription' => true, 'purchase_price' => 95.00, 'sale_price' => 145.00, 'minimum_stock' => 10, 'reorder_point' => 20],

            // General
            ['generic_name' => 'Omeprazol', 'commercial_name' => 'Losec', 'category' => 'Medicamentos Gerais', 'pharmaceutical_form' => 'Cápsula', 'dosage' => '20mg', 'unit' => 'caixa', 'barcode' => '6009700120064', 'requires_prescription' => true, 'purchase_price' => 140.00, 'sale_price' => 210.00, 'minimum_stock' => 10, 'reorder_point' => 20],
            ['generic_name' => 'Loratadina', 'commercial_name' => 'Claritine', 'category' => 'Medicamentos Gerais', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '10mg', 'unit' => 'caixa', 'barcode' => '6009700120071', 'purchase_price' => 75.00, 'sale_price' => 115.00, 'minimum_stock' => 10, 'reorder_point' => 20],
            ['generic_name' => 'Metformina', 'commercial_name' => 'Glucophage', 'category' => 'Medicamentos Gerais', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '500mg', 'unit' => 'caixa', 'barcode' => '6009700120088', 'requires_prescription' => true, 'purchase_price' => 65.00, 'sale_price' => 100.00, 'minimum_stock' => 15, 'reorder_point' => 30],
            ['generic_name' => 'Amlodipina', 'commercial_name' => 'Norvasc', 'category' => 'Medicamentos Gerais', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '5mg', 'unit' => 'caixa', 'barcode' => '6009700120095', 'requires_prescription' => true, 'purchase_price' => 95.00, 'sale_price' => 145.00, 'minimum_stock' => 10, 'reorder_point' => 20],

            // Vitamins
            ['generic_name' => 'Vitamina C', 'commercial_name' => 'Cebion', 'category' => 'Vitaminas e Suplementos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '500mg', 'unit' => 'frasco', 'barcode' => '6009700120101', 'purchase_price' => 55.00, 'sale_price' => 85.00, 'minimum_stock' => 20, 'reorder_point' => 40],
            ['generic_name' => 'Sulfato Ferroso', 'commercial_name' => 'Ferrograd', 'category' => 'Vitaminas e Suplementos', 'pharmaceutical_form' => 'Comprimido', 'dosage' => '200mg', 'unit' => 'frasco', 'barcode' => '6009700120118', 'purchase_price' => 45.00, 'sale_price' => 70.00, 'minimum_stock' => 10, 'reorder_point' => 20],

            // Dermatology
            ['generic_name' => 'Clotrimazol', 'commercial_name' => 'Canesten', 'category' => 'Dermatológicos', 'pharmaceutical_form' => 'Creme', 'dosage' => '1%', 'unit' => 'tubo', 'barcode' => '6009700120125', 'purchase_price' => 80.00, 'sale_price' => 125.00, 'minimum_stock' => 8, 'reorder_point' => 15],
            ['generic_name' => 'Hidrocortisona', 'commercial_name' => 'Cortaid', 'category' => 'Dermatológicos', 'pharmaceutical_form' => 'Creme', 'dosage' => '1%', 'unit' => 'tubo', 'barcode' => '6009700120132', 'requires_prescription' => true, 'purchase_price' => 65.00, 'sale_price' => 100.00, 'minimum_stock' => 5, 'reorder_point' => 10],

            // Pediatrics
            ['generic_name' => 'Paracetamol Pediátrico', 'commercial_name' => 'Tylenol Baby', 'category' => 'Pediátricos', 'pharmaceutical_form' => 'Xarope', 'dosage' => '120mg/5ml', 'unit' => 'frasco', 'barcode' => '6009700120149', 'purchase_price' => 70.00, 'sale_price' => 110.00, 'minimum_stock' => 10, 'reorder_point' => 20],

            // Dressing
            ['generic_name' => 'Gaze Esterilizada', 'commercial_name' => null, 'category' => 'Material de Penso', 'pharmaceutical_form' => null, 'dosage' => null, 'unit' => 'caixa', 'barcode' => '6009700120156', 'purchase_price' => 30.00, 'sale_price' => 50.00, 'minimum_stock' => 20, 'reorder_point' => 40],
        ];

        $productModels = [];
        foreach ($products as $pd) {
            $catId = $cats[$pd['category']] ?? null;
            $product = Product::firstOrCreate(['barcode' => $pd['barcode']], [
                'generic_name'          => $pd['generic_name'],
                'commercial_name'       => $pd['commercial_name'],
                'category_id'           => $catId,
                'pharmaceutical_form'   => $pd['pharmaceutical_form'],
                'dosage'                => $pd['dosage'],
                'unit'                  => $pd['unit'],
                'barcode'               => $pd['barcode'],
                'requires_prescription' => $pd['requires_prescription'] ?? false,
                'purchase_price'        => $pd['purchase_price'],
                'sale_price'            => $pd['sale_price'],
                'minimum_stock'         => $pd['minimum_stock'],
                'reorder_point'         => $pd['reorder_point'],
                'is_active'             => true,
                'created_by'            => $admin->id,
            ]);
            $productModels[] = $product;
        }

        // ── Product Batches (stock) ────────────────────────────────────────
        foreach ($productModels as $i => $product) {
            if ($product->batches()->exists()) continue;

            $batch = ProductBatch::create([
                'product_id'        => $product->id,
                'branch_id'         => $branch->id,
                'supplier_id'       => $supplierModels[$i % 3]->id,
                'batch_number'      => 'LOTE-' . now()->format('Ym') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'expiry_date'       => now()->addMonths(rand(8, 24)),
                'quantity_received' => rand(50, 200),
                'quantity_current'  => rand(30, 150),
                'purchase_price'    => $product->purchase_price,
                'sale_price'        => $product->sale_price,
                'status'            => 'available',
                'created_by'        => $admin->id,
            ]);

            // Record stock movement
            StockMovement::create([
                'product_id'      => $product->id,
                'product_batch_id'=> $batch->id,
                'branch_id'       => $branch->id,
                'movement_type'   => 'entry',
                'quantity'        => $batch->quantity_current,
                'reference_type'  => 'initial_stock',
                'reference_id'    => null,
                'notes'           => 'Stock inicial',
                'created_by'      => $admin->id,
            ]);
        }

        // ── Customers ─────────────────────────────────────────────────────
        $customers = [
            ['name' => 'Maria José Macuácua', 'phone' => '+258 84 111 2233', 'nuit' => '141234567', 'email' => 'maria.macuacua@gmail.com', 'credit_limit' => 2000],
            ['name' => 'António Carlos Mucavele', 'phone' => '+258 82 333 4455', 'nuit' => '141345678', 'credit_limit' => 3000],
            ['name' => 'Fátima Salomão Nhantumbo', 'phone' => '+258 86 555 6677', 'credit_limit' => 1500],
            ['name' => 'Pedro Machava', 'phone' => '+258 84 777 8899', 'nuit' => '141456789', 'credit_limit' => 5000],
            ['name' => 'Lurdes Cossa', 'phone' => '+258 82 999 0011', 'credit_limit' => 1000],
            ['name' => 'João Baptista Tembe', 'phone' => '+258 86 111 3355', 'credit_limit' => 4000],
            ['name' => 'Estela Chirindza', 'phone' => '+258 84 222 4466', 'credit_limit' => 2500],
            ['name' => 'Carlos Mavundza', 'phone' => '+258 82 444 6688', 'nuit' => '141567890', 'credit_limit' => 3500],
        ];

        $customerModels = collect($customers)->map(fn($c) =>
            Customer::firstOrCreate(['name' => $c['name']], array_merge($c, ['is_active' => true]))
        );

        // ── Insurance Companies ────────────────────────────────────────────
        $insurers = [
            [
                'name'                 => 'Seguradora Global Moçambique',
                'nuit'                 => '400111222',
                'phone'                => '+258 21 350 000',
                'email'                => 'seguros@globalmoz.co.mz',
                'billing_cycle'        => 'monthly',
                'default_coverage_pct' => 80,
                'requires_preauth'     => false,
                'is_active'            => true,
            ],
            [
                'name'                 => 'MozSaúde Seguros',
                'nuit'                 => '400222333',
                'phone'                => '+258 21 450 000',
                'email'                => 'saude@mozsaude.co.mz',
                'billing_cycle'        => 'monthly',
                'default_coverage_pct' => 70,
                'requires_preauth'     => true,
                'is_active'            => true,
            ],
            [
                'name'                 => 'EMOSE — Empresa Moçambicana de Seguros',
                'nuit'                 => '400333444',
                'phone'                => '+258 21 355 555',
                'email'                => 'info@emose.co.mz',
                'billing_cycle'        => 'monthly',
                'default_coverage_pct' => 75,
                'requires_preauth'     => false,
                'is_active'            => true,
            ],
        ];

        $insurerModels = collect($insurers)->map(fn($ins) =>
            InsuranceCompany::firstOrCreate(['name' => $ins['name']], $ins)
        );

        // Coverage rules for Seguradora Global
        $sgm = $insurerModels[0];
        $antibiotics = ProductCategory::where('name', 'Antibióticos')->first();
        if ($antibiotics && !$sgm->coverageRules()->where('product_category_id', $antibiotics->id)->exists()) {
            InsuranceCoverageRule::create([
                'insurance_company_id'  => $sgm->id,
                'product_category_id'   => $antibiotics->id,
                'coverage_percentage'   => 100,
                'is_active'             => true,
            ]);
        }

        // ── Insurance Cards ────────────────────────────────────────────────
        $cardData = [
            [$customerModels[0], $insurerModels[0], 'SGM-2024-001001', 'AP-001001', 'Ministério da Saúde'],
            [$customerModels[1], $insurerModels[0], 'SGM-2024-001002', 'AP-001001', 'Ministério da Saúde'],
            [$customerModels[2], $insurerModels[1], 'MSZ-2024-002001', 'AP-002001', 'Vodacom Moçambique'],
            [$customerModels[3], $insurerModels[1], 'MSZ-2024-002002', 'AP-002001', 'Vodacom Moçambique'],
            [$customerModels[4], $insurerModels[2], 'EMO-2024-003001', 'AP-003001', 'BCI Banco'],
            [$customerModels[5], $insurerModels[2], 'EMO-2024-003002', 'AP-003001', 'BCI Banco'],
        ];

        foreach ($cardData as [$customer, $insurer, $cardNum, $policy, $employer]) {
            InsuranceCard::firstOrCreate(['card_number' => $cardNum], [
                'customer_id'           => $customer->id,
                'insurance_company_id'  => $insurer->id,
                'card_number'           => $cardNum,
                'policy_number'         => $policy,
                'employer_name'         => $employer,
                'coverage_limit_annual' => 50000.00,
                'expiry_date'           => now()->addYear(),
                'valid_from'            => now()->subYear(),
                'is_active'             => true,
            ]);
        }

        // ── Purchase Orders ────────────────────────────────────────────────
        $po = PurchaseOrder::firstOrCreate(['po_number' => 'PO-20260101-0001'], [
            'po_number'   => 'PO-20260101-0001',
            'branch_id'   => $branch->id,
            'supplier_id' => $supplierModels[0]->id,
            'status'      => 'received',
            'order_date'  => now()->subMonths(2),
            'received_date' => now()->subMonths(2)->addDays(3),
            'total_amount'  => 12500.00,
            'created_by'    => $admin->id,
        ]);

        if (!$po->items()->exists()) {
            foreach (array_slice($productModels, 0, 5) as $i => $prod) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id'        => $prod->id,
                    'quantity_ordered'  => 100,
                    'quantity_received' => 100,
                    'unit_price'        => $prod->purchase_price,
                ]);
            }
        }

        // Draft PO
        $po2 = PurchaseOrder::firstOrCreate(['po_number' => 'PO-20260424-0001'], [
            'po_number'   => 'PO-20260424-0001',
            'branch_id'   => $branch->id,
            'supplier_id' => $supplierModels[1]->id,
            'status'      => 'draft',
            'order_date'  => now(),
            'total_amount'=> 8700.00,
            'created_by'  => $admin->id,
        ]);

        if (!$po2->items()->exists()) {
            foreach (array_slice($productModels, 3, 4) as $prod) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po2->id,
                    'product_id'        => $prod->id,
                    'quantity_ordered'  => 50,
                    'quantity_received' => 0,
                    'unit_price'        => $prod->purchase_price,
                ]);
            }
        }

        // ── Sales ──────────────────────────────────────────────────────────
        if (Sale::count() < 5) {
            $this->createDemoSales($productModels, $customerModels, $insurerModels, $branch, $admin);
        }

        $this->command->info('Demo data seeded successfully.');
    }

    private function createDemoSales($products, $customers, $insurers, $branch, $admin): void
    {
        $saleScenarios = [
            // Cash sale, walk-in
            ['customer' => null, 'payment' => 'cash', 'items_count' => 2, 'days_ago' => 1],
            // M-Pesa sale
            ['customer' => 0, 'payment' => 'mpesa', 'items_count' => 3, 'days_ago' => 2],
            // Insurance sale
            ['customer' => 0, 'payment' => 'insurance', 'items_count' => 2, 'days_ago' => 3],
            // Credit sale
            ['customer' => 1, 'payment' => 'credit', 'items_count' => 1, 'days_ago' => 4],
            // Cash sale with customer
            ['customer' => 2, 'payment' => 'cash', 'items_count' => 4, 'days_ago' => 5],
            // Insurance sale 2
            ['customer' => 2, 'payment' => 'insurance', 'items_count' => 2, 'days_ago' => 6],
            // Cash sale
            ['customer' => null, 'payment' => 'cash', 'items_count' => 1, 'days_ago' => 7],
            // e-Mola
            ['customer' => 3, 'payment' => 'emola', 'items_count' => 2, 'days_ago' => 8],
        ];

        foreach ($saleScenarios as $idx => $scenario) {
            $customer       = $scenario['customer'] !== null ? $customers[$scenario['customer']] : null;
            $insuranceCard  = null;
            $insuranceAmount = 0;
            $totalAmount    = 0;

            // Pick random products for this sale
            $saleProducts = collect($products)->shuffle()->take($scenario['items_count']);

            // Calculate totals
            $items = $saleProducts->map(function ($p) {
                $qty     = rand(1, 3);
                $price   = (float) $p->sale_price;
                $subtotal = $qty * $price;
                return [
                    'product'  => $p,
                    'quantity' => $qty,
                    'price'    => $price,
                    'subtotal' => $subtotal,
                ];
            })->values();

            $totalAmount = $items->sum('subtotal');

            if ($scenario['payment'] === 'insurance' && $customer) {
                $insuranceCard = InsuranceCard::where('customer_id', $customer->id)->first();
                $insurancePct  = $insuranceCard ? ($insuranceCard->insuranceCompany->default_coverage_pct / 100) : 0;
                $insuranceAmount = round($totalAmount * $insurancePct, 2);
            }

            $saleNumber = 'VD-' . now()->subDays($scenario['days_ago'])->format('Ymd') . '-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'sale_number'       => $saleNumber,
                'branch_id'         => $branch->id,
                'customer_id'       => $customer?->id,
                'insurance_card_id' => $insuranceCard?->id,
                'cashier_id'        => $admin->id,
                'status'            => 'completed',
                'subtotal'          => $totalAmount,
                'total_amount'      => $totalAmount,
                'discount_amount'   => 0,
                'insurance_amount'  => $insuranceAmount,
                'customer_amount'   => $totalAmount - $insuranceAmount,
                'created_at'        => now()->subDays($scenario['days_ago'])->setTime(rand(8, 18), rand(0, 59)),
                'created_by'        => $admin->id,
            ]);

            foreach ($items as $item) {
                $batch = $item['product']->batches()->where('status', 'available')->where('quantity_current', '>', 0)->first();
                if (!$batch) continue;

                $itemInsurance = $insuranceAmount > 0 ? round($item['subtotal'] * ($insurancePct ?? 0), 2) : 0;

                SaleItem::create([
                    'sale_id'                => $sale->id,
                    'product_id'             => $item['product']->id,
                    'product_batch_id'       => $batch->id,
                    'quantity'               => $item['quantity'],
                    'unit_price'             => $item['price'],
                    'discount_percentage'    => 0,
                    'subtotal'               => $item['subtotal'],
                    'insurance_coverage_pct' => $insuranceAmount > 0 ? ($insurancePct * 100) : 0,
                    'insurance_amount'       => $itemInsurance,
                    'customer_amount'        => $item['subtotal'] - $itemInsurance,
                ]);

                $batch->decrement('quantity_current', $item['quantity']);

                StockMovement::create([
                    'product_id'      => $item['product']->id,
                    'product_batch_id'=> $batch->id,
                    'branch_id'       => $branch->id,
                    'movement_type'   => 'exit',
                    'quantity'        => $item['quantity'],
                    'reference_type'  => 'sale',
                    'reference_id'    => $sale->id,
                    'created_by'      => $admin->id,
                ]);
            }

            $method  = $scenario['payment'];
            $custPays = $totalAmount - $insuranceAmount;

            SalePayment::create([
                'sale_id'        => $sale->id,
                'payment_method' => $method === 'insurance' ? 'cash' : $method,
                'amount'         => $method === 'credit' ? $custPays : ($method === 'insurance' ? $custPays : $totalAmount),
                'status'         => 'completed',
            ]);

            if ($method === 'insurance' && $insuranceAmount > 0 && $insuranceCard) {
                SalePayment::create([
                    'sale_id'        => $sale->id,
                    'payment_method' => 'insurance',
                    'amount'         => $insuranceAmount,
                    'status'         => 'completed',
                ]);

                \App\Models\InsuranceClaim::create([
                    'claim_number'        => 'CLM-' . now()->subDays($scenario['days_ago'])->format('Ym') . '-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                    'sale_id'             => $sale->id,
                    'insurance_card_id'   => $insuranceCard->id,
                    'insurance_company_id'=> $insuranceCard->insurance_company_id,
                    'amount_claimed'      => $insuranceAmount,
                    'status'             => collect(['pending', 'submitted', 'approved', 'paid'])->random(),
                    'created_by'          => $admin->id,
                ]);
            }
        }
    }
}
