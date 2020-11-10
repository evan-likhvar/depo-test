<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class UserDeposit
{
    /**
     * @param User $user
     * @param float $amount
     */
    public function createDeposit(User $user, float $amount)
    {
        DB::transaction(function () use ($user, $amount) {

            $user->wallet->balance -= $amount;
            $user->wallet->save();

            $wallet = Wallet::where('user_id', $user->id)->first();
            if ($wallet->balance < 0)
                throw new \DomainException('Insufficient points!');

            $deposit = new Deposit([
                'wallet_id' => $user->wallet->id,
                'invested' => $amount,
                'percent' => Deposit::DEFAULT_PERCENT,
                'active' => 1,
            ]);
            $user->deposits()->save($deposit);

            $transaction = new Transaction([
                'type' => Transaction::TRANSACTION_CREATE_DEPOSIT,
                'wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'deposit_id' => $deposit->id
            ]);
            $user->transactions()->save($transaction);

            },3);
    }
}
