<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_number', 'branch_id', 'supplier_id', 'status', 'order_date',
        'expected_date', 'received_date', 'subtotal', 'tax_amount',
        'total_amount', 'invoice_number', 'notes', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'order_date'    => 'date',
            'expected_date' => 'date',
            'received_date' => 'date',
            'subtotal'      => 'decimal:2',
            'tax_amount'    => 'decimal:2',
            'total_amount'  => 'decimal:2',
        ];
    }

    public const STATUSES = [
        'draft'     => 'Rascunho',
        'sent'      => 'Enviado',
        'partial'   => 'Parcialmente Recebido',
        'received'  => 'Recebido',
        'cancelled' => 'Cancelado',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function productBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generatePoNumber(): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');
        $last  = static::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return sprintf('PO-%s%s-%04d', $year, $month, $last);
    }
}
