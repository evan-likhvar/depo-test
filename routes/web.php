<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'edit'])->middleware('auth')->name('wallet.enter.get');
Route::post('/wallet', [App\Http\Controllers\WalletController::class, 'update'])->middleware('auth')->name('wallet.enter.post');

Route::get('/deposit', [App\Http\Controllers\DepositController::class, 'create'])->middleware('auth')->name('deposit.create.get');
Route::post('/deposit', [App\Http\Controllers\DepositController::class, 'store'])->middleware('auth')->name('deposit.create.post');
Route::get('/deposit/index', [App\Http\Controllers\DepositController::class, 'index'])->middleware('auth')->name('deposit.index');

Route::get('/transaction/index', [App\Http\Controllers\TransactionController::class, 'index'])->middleware('auth')->name('transaction.index');
