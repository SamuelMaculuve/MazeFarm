<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'product_batch_id', 'quantity',
        'unit_price', 'discount_percentage', 'subtotal',
        'insurance_coverage_pct', 'insurance_amount', 'customer_amount',
        'prescription_validated',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'              => 'decimal:2',
            'discount_percentage'     => 'decimal:2',
            'subtotal'                => 'decimal:2',
            'insurance_coverage_pct'  => 'decimal:2',
            'insurance_amount'        => 'decimal:2',
            'customer_amount'         => 'decimal:2',
            'prescription_validated'  => 'boolean',
        ];
    }

    public function sale(): BelongsTo { return $this->belongsTo(Sale::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function batch(): BelongsTo { return $this->belongsTo(ProductBatch::class, 'product_batch_id'); }
}
