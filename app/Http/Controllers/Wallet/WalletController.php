<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Interfaces\WalletInterface;
use Illuminate\Http\Request;

class WalletController extends Controller
{
   public $walletRepository;

   public function __construct(WalletInterface $walletRepository)
   {
      $this->walletRepository = $walletRepository;
   }


   public function transfer(Request $request)
   {
      return $this->walletRepository->payToWallet(
         $request->pin,
         $request->sender,
         $request->receiver,
         $request->amount
      );
   }

   public function transactions(Request $request)
   {
      $transactions = $request->user()->wallet->transactions;

      // dd($transactions);

      return response()->json([
         'status' => true,
         'data' => $transactions
      ]);
   }
}
