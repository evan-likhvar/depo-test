<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserBalance
{
    /**
     * @param User $user
     * @param float $amount
     */
    public function addBalance(User $user, float $amount)
    {
        DB::transaction(function () use ($user, $amount) {

            $transaction = new Transaction([
                'type' => Transaction::TRANSACTION_ENTER,
                'wallet_id' => $user->wallet->id,
                'amount' => $amount
            ]);

            $user->transactions()->save($transaction);

            $user->wallet->balance += $amount;
            $user->wallet->save();

            },3);
    }
}
