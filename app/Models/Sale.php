<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sale_number', 'branch_id', 'customer_id', 'insurance_card_id', 'status',
        'has_prescription', 'prescription_number', 'prescription_doctor', 'prescription_date',
        'subtotal', 'discount_amount', 'tax_amount', 'total_amount',
        'customer_amount', 'insurance_amount', 'insurance_auth_code',
        'insurance_rejection_reason', 'notes', 'cashier_id', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'has_prescription'  => 'boolean',
            'prescription_date' => 'date',
            'subtotal'          => 'decimal:2',
            'discount_amount'   => 'decimal:2',
            'tax_amount'        => 'decimal:2',
            'total_amount'      => 'decimal:2',
            'customer_amount'   => 'decimal:2',
            'insurance_amount'  => 'decimal:2',
        ];
    }

    public const STATUSES = [
        'completed' => 'Concluída',
        'cancelled'  => 'Cancelada',
        'refunded'   => 'Reembolsada',
    ];

    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function insuranceCard(): BelongsTo { return $this->belongsTo(InsuranceCard::class); }
    public function cashier(): BelongsTo { return $this->belongsTo(User::class, 'cashier_id'); }

    public function items(): HasMany { return $this->hasMany(SaleItem::class); }
    public function payments(): HasMany { return $this->hasMany(SalePayment::class); }
    public function claims(): HasMany { return $this->hasMany(InsuranceClaim::class); }

    public static function generateSaleNumber(): string
    {
        $prefix = 'VD-' . now()->format('Ymd') . '-';
        $last   = static::where('sale_number', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
