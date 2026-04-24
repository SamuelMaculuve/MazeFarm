<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceClaim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_number', 'sale_id', 'insurance_card_id', 'insurance_company_id',
        'authorization_code', 'amount_claimed', 'amount_approved', 'amount_paid',
        'status', 'rejection_reason', 'submitted_at', 'approved_at', 'paid_at',
        'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount_claimed'  => 'decimal:2',
            'amount_approved' => 'decimal:2',
            'amount_paid'     => 'decimal:2',
            'submitted_at'    => 'datetime',
            'approved_at'     => 'datetime',
            'paid_at'         => 'datetime',
        ];
    }

    public const STATUSES = [
        'pending'   => 'Pendente',
        'submitted' => 'Submetido',
        'approved'  => 'Aprovado',
        'paid'      => 'Pago',
        'rejected'  => 'Rejeitado',
    ];

    public function sale(): BelongsTo { return $this->belongsTo(Sale::class); }
    public function insuranceCard(): BelongsTo { return $this->belongsTo(InsuranceCard::class); }
    public function insuranceCompany(): BelongsTo { return $this->belongsTo(InsuranceCompany::class); }

    public static function generateClaimNumber(): string
    {
        $prefix = 'CLM-' . now()->format('Ym') . '-';
        $last   = static::where('claim_number', 'like', $prefix . '%')->withTrashed()->count() + 1;
        return $prefix . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
