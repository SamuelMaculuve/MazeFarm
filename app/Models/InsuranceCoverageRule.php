<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceCoverageRule extends Model
{
    protected $fillable = [
        'insurance_company_id', 'product_id', 'product_category_id',
        'coverage_percentage', 'requires_preauth', 'notes', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_preauth'    => 'boolean',
            'is_active'           => 'boolean',
            'coverage_percentage' => 'decimal:2',
        ];
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
