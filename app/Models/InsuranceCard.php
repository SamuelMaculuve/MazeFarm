<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'insurance_company_id', 'card_number', 'policy_number',
        'employee_number', 'employer_name', 'coverage_pct', 'copay_amount',
        'coverage_limit_annual', 'monthly_limit', 'coverage_used',
        'expiry_date', 'valid_from', 'notes', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date'          => 'date',
            'valid_from'           => 'date',
            'is_active'            => 'boolean',
            'coverage_pct'         => 'decimal:2',
            'copay_amount'         => 'decimal:2',
            'coverage_limit_annual'=> 'decimal:2',
            'monthly_limit'        => 'decimal:2',
            'coverage_used'        => 'decimal:2',
        ];
    }

    public function getValidUntilAttribute()
    {
        return $this->expiry_date;
    }

    public function getAnnualLimitAttribute()
    {
        return $this->coverage_limit_annual;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getIsValidAttribute(): bool
    {
        return $this->is_active && $this->expiry_date->isFuture();
    }

    public function getRemainingLimitAttribute(): ?float
    {
        if ($this->coverage_limit_annual === null) {
            return null;
        }
        return max(0, (float) $this->coverage_limit_annual - (float) $this->coverage_used);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)->where('expiry_date', '>=', now());
    }
}
