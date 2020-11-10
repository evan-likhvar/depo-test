<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepositTransactionRequest;
use App\Services\UserDeposit;
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
        return view('deposit.index')->with(['user'=>Auth::user()]);
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
     * @param UserDeposit $userDeposit
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store(CreateDepositTransactionRequest $request, UserDeposit $userDeposit)
    {
        $userDeposit->createDeposit(Auth::user(), $request->input('amount'));

        return view('home');
    }
}
