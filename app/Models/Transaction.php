<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
   protected $fillable = [
    'transaction_ref',
    'payer_name',
    'referenceId',
    'user_id',
    'performed_by',
    'amount',
    'fee',
    'net_amount',
    'description',
    'type',
    'status',
    'metadata',
    'created_at',
    'updated_at',
];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
