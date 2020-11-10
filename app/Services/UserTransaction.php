<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class UserTransaction
{
    /**
     * @param User $user
     * @param float $amount
     */
    public function createEnter(User $user, float $amount)
    {
        DB::transaction(function () use ($user, $amount) {

            $this->createTransaction([
                'type' => Transaction::TRANSACTION_ENTER,
                'wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'user_id' => $user->id
            ]);

            $user->wallet->balance += $amount;
            $user->wallet->save();

        }, 3);
    }

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

            $this->createTransaction([
                'type' => Transaction::TRANSACTION_CREATE_DEPOSIT,
                'wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'deposit_id' => $deposit->id,
                'user_id' => $user->id
            ]);

        }, 2);
    }

    /**
     * @param Deposit $deposit
     */
    public function createAccrue(Deposit $deposit)
    {
        DB::transaction(function () use ($deposit) {
            $amount = $deposit->invested * $deposit->percent / 100;
            $wallet = Wallet::findorfail($deposit->wallet_id);

            $wallet->balance += $amount;
            $wallet->save();

            $deposit->accrue_times += 1;
            if ($deposit->accrue_times >= config('site-param.max_accrue_times'))
                $deposit->active = 0;

            $deposit->save();

            $this->createTransaction([
                'type' => $deposit->accrue <= config('site-param.max_accrue_times')
                    ? Transaction::TRANSACTION_ACCRUE
                    : Transaction::TRANSACTION_CLOSE_DEPOSIT,
                'wallet_id' => $deposit->wallet_id,
                'amount' => $amount,
                'deposit_id' => $deposit->id,
                'user_id' => $deposit->user_id
            ]);

        }, 2);
    }

    /**
     * @param array $transactionData
     * @return bool
     */
    private function createTransaction(array $transactionData)
    {
        return (new Transaction($transactionData))->save();
    }
}
