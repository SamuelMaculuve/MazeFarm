<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'product_batch_id', 'branch_id', 'movement_type',
        'quantity', 'quantity_before', 'quantity_after', 'unit_cost',
        'reference_type', 'reference_id', 'notes', 'created_by',
    ];

    public const TYPES = [
        'entry'         => 'Entrada',
        'exit'          => 'Saída',
        'adjustment'    => 'Ajuste',
        'transfer_in'   => 'Transferência Entrada',
        'transfer_out'  => 'Transferência Saída',
        'sale'          => 'Venda',
        'return'        => 'Devolução',
        'expired'       => 'Expirado',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
