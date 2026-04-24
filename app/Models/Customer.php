<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'name', 'nuit', 'phone', 'email', 'address',
        'date_of_birth', 'gender', 'notes', 'credit_limit',
        'credit_balance', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'  => 'date',
            'is_active'      => 'boolean',
            'credit_limit'   => 'decimal:2',
            'credit_balance' => 'decimal:2',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function insuranceCards(): HasMany
    {
        return $this->hasMany(InsuranceCard::class);
    }

    public function activeInsuranceCards(): HasMany
    {
        return $this->hasMany(InsuranceCard::class)
            ->where('is_active', true)
            ->where('expiry_date', '>=', now());
    }

    public function creditSettlements(): HasMany
    {
        return $this->hasMany(CreditSettlement::class);
    }

    public function getCreditAvailableAttribute(): float
    {
        return max(0, (float) $this->credit_limit - (float) $this->credit_balance);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('nuit', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
