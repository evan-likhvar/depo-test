<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use DomainException;
use Illuminate\Support\Facades\DB;

class UserTransaction
{
    /**
     * Balance replenishment
     *
     * @param User $user
     * @param float $amount
     */
    public function createEnter(User $user, float $amount): void
    {
        DB::transaction(function () use ($user, $amount) {

            $this->changeBalance($user->wallet, $amount);

            $this->createTransaction([
                'type' => Transaction::TRANSACTION_ENTER,
                'wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'user_id' => $user->id
            ]);

        }, config('site-param.transaction_attempts'));
    }

    /**
     * Creates a new user deposit
     *
     * @param User $user
     * @param float $amount
     */
    public function createDeposit(User $user, float $amount): void
    {
        DB::transaction(function () use ($user, $amount) {

            if ($user->wallet->balance < $amount)
                throw new DomainException('Insufficient points!');

            $this->changeBalance($user->wallet, -$amount);

            $deposit = new Deposit([
                'wallet_id' => $user->wallet->id,
                'invested' => $amount,
                'percent' => Deposit::DEFAULT_PERCENT,
                'active' => true,
            ]);
            $user->deposits()->save($deposit);

            $this->createTransaction([
                'type' => Transaction::TRANSACTION_CREATE_DEPOSIT,
                'wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'deposit_id' => $deposit->id,
                'user_id' => $user->id
            ]);

        }, config('site-param.transaction_attempts'));
    }

    /**
     * Accrual of interest on the deposit
     *
     * @param Deposit $deposit
     */
    public function createAccrue(Deposit $deposit): void
    {
        DB::transaction(function () use ($deposit) {
            $amount = $deposit->invested * $deposit->percent / 100;
            $wallet = Wallet::findOrFail($deposit->wallet_id);

            $this->changeBalance($wallet, $amount);

            $deposit->accrue_times++;
            if ($deposit->accrue_times >= config('site-param.max_accrue_times'))
                $deposit->active = false;
            $deposit->save();

            $this->createTransaction([
                'type' => $deposit->accrue_times >= config('site-param.max_accrue_times')
                    ? Transaction::TRANSACTION_CLOSE_DEPOSIT
                    : Transaction::TRANSACTION_ACCRUE,
                'wallet_id' => $deposit->wallet_id,
                'amount' => $amount,
                'deposit_id' => $deposit->id,
                'user_id' => $deposit->user_id
            ]);

        }, config('site-param.transaction_attempts'));
    }

    /**
     * Creates the transaction
     *
     * @param array $transactionData
     */
    private function createTransaction(array $transactionData): void
    {
        (new Transaction($transactionData))->save();
    }

    /**
     * Changes the user's balance
     *
     * @param Wallet $wallet
     * @param float $amount
     */
    private function changeBalance(Wallet $wallet, float $amount): void
    {
        $wallet->balance += $amount;
        $wallet->save();
    }
}
