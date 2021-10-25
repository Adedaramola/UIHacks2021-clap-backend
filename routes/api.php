<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest'])->prefix('auth')->group(function () {
   Route::post('register', [RegisterController::class, 'store']);
   Route::post('login', [LoginController::class, 'login'])->name('login');
});


Route::middleware(['auth'])->group(function () {
   Route::get('user', UserProfileController::class)->name('user');
   Route::post('logout', [LoginController::class, 'logout']);
   Route::post('email/verify/{user}', [VerifyEmailController::class, 'verify'])
      ->name('verification.verify')
      ->middleware('signed');

   Route::middleware(['verified'])->group(function () {


      // Route::post('wallet/deposit', [WalletController::class, 'deposit']);
      // Route::post('wallet/withdraw', [WalletController::class, 'withdraw']);
      // Route::get('wallet/transactions', [WalletController::class, 'transactions']);
   });
});

Route::post('wallet/transfer', [WalletController::class, 'transfer']);
