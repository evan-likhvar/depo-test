<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const TRANSACTION_ENTER = 'enter';
    const TRANSACTION_CREATE_DEPOSIT = 'create_deposit';
    const TRANSACTION_ACCRUE = 'accrue';
    const TRANSACTION_CLOSE_DEPOSIT = 'close_deposit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'wallet_id',
        'deposit_id',
        'type',
        'amount',
    ];

    /**
     * Get transaction's owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get transaction's wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallet');
    }

    /**
     * Get transaction's deposit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deposit()
    {
        return $this->belongsTo('App\Models\Deposit');
    }
}
