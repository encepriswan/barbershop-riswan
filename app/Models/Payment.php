<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'method',
        'status',
        'proof'
    ];

    /*
    |--------------------------------------------------------------------------
    | CONSTANT (BEST PRACTICE)
    |--------------------------------------------------------------------------
    */

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_REJECTED = 'rejected';

    const METHOD_CASH = 'cash';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_EWALLET = 'ewallet';
    const METHOD_QRIS = 'qris';

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR (AUTO URL)
    |--------------------------------------------------------------------------
    */

    public function getProofUrlAttribute()
    {
        return $this->proof
            ? asset('storage/' . $this->proof)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (QUERY CLEAN)
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER (LOGIC)
    |--------------------------------------------------------------------------
    */

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCash()
    {
        return $this->method === self::METHOD_CASH;
    }

    public function isTransfer()
    {
        return $this->method === self::METHOD_TRANSFER;
    }

    public function isEwallet()
    {
        return $this->method === self::METHOD_EWALLET;
    }

    public function isQris()
    {
        return $this->method === self::METHOD_QRIS;
    }
}