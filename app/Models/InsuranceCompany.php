<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCompany extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'nuit', 'contact_person', 'phone', 'email', 'address',
        'billing_cycle', 'requires_preauth', 'default_coverage_pct',
        'notes', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'requires_preauth'    => 'boolean',
            'is_active'           => 'boolean',
            'default_coverage_pct'=> 'decimal:2',
        ];
    }

    public function insuranceCards(): HasMany
    {
        return $this->hasMany(InsuranceCard::class);
    }

    public function coverageRules(): HasMany
    {
        return $this->hasMany(InsuranceCoverageRule::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class);
    }

    public function getCoverageForProduct(Product $product): float
    {
        $rule = $this->coverageRules()
            ->where('is_active', true)
            ->where(function ($q) use ($product) {
                $q->where('product_id', $product->id)
                  ->orWhere('product_category_id', $product->category_id)
                  ->orWhereNull('product_id');
            })
            ->orderByRaw('product_id IS NOT NULL DESC, product_category_id IS NOT NULL DESC')
            ->first();

        return $rule ? (float) $rule->coverage_percentage : (float) $this->default_coverage_pct;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
