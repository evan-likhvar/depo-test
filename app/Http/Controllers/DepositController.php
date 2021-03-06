<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepositTransactionRequest;
use App\Services\UserTransaction;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('deposit.index')->with(['user' => Auth::user()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('deposit.deposit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateDepositTransactionRequest $request
     * @param UserTransaction $userTransaction
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(CreateDepositTransactionRequest $request, UserTransaction $userTransaction)
    {
        try {
            $userTransaction->createDeposit(Auth::user(), $request->input('amount'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return view('home');
    }
}
