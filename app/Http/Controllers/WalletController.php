<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterTransactionRequest;
use App\Models\Wallet;
use App\Services\UserTransaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        return view('wallet.wallet');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EnterTransactionRequest $request
     * @param UserTransaction $userTransaction
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function update(EnterTransactionRequest $request, UserTransaction $userTransaction)
    {
        $userTransaction->createEnter(Auth::user(), $request->input('amount'));

        return view('home');
    }
}
