<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'generic_name', 'commercial_name', 'category_id', 'pharmaceutical_form',
        'dosage', 'unit', 'barcode', 'fnm_code', 'requires_prescription',
        'is_controlled', 'is_narcotic', 'minimum_stock', 'reorder_point',
        'purchase_price', 'sale_price', 'description', 'manufacturer',
        'country_of_origin', 'is_active', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'requires_prescription' => 'boolean',
            'is_controlled'         => 'boolean',
            'is_narcotic'           => 'boolean',
            'is_active'             => 'boolean',
            'purchase_price'        => 'decimal:2',
            'sale_price'            => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function activeBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class)
            ->where('status', 'available')
            ->where('quantity_current', '>', 0)
            ->orderBy('expiry_date');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function coverageRules(): HasMany
    {
        return $this->hasMany(InsuranceCoverageRule::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->batches()
            ->where('status', 'available')
            ->sum('quantity_current');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->minimum_stock;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('generic_name', 'like', "%{$term}%")
              ->orWhere('commercial_name', 'like', "%{$term}%")
              ->orWhere('barcode', 'like', "%{$term}%");
        });
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw(
            '(SELECT COALESCE(SUM(pb.quantity_current),0) FROM product_batches pb WHERE pb.product_id = products.id AND pb.status = "available") <= products.minimum_stock'
        );
    }
}
