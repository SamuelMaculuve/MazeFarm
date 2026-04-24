<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePayment extends Model
{
    protected $fillable = [
        'sale_id', 'payment_method', 'amount', 'reference_number', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public const METHODS = [
        'cash'      => 'Dinheiro',
        'mpesa'     => 'M-Pesa',
        'emola'     => 'e-Mola',
        'card'      => 'Cartão',
        'insurance' => 'Seguradora',
        'credit'    => 'Fiado',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
