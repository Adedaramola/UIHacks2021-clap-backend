<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
   public function create($txn_type, $purpose, $amount, $wallet_id, $balance_before, $balance_after): void
   {
      Transaction::create([
         'txn_type' => $txn_type,
         'purpose' => $purpose,
         'amount' => $amount,
         'wallet_id' => $wallet_id,
         'balance_before' => $balance_before,
         'balance_after' => $balance_after
      ]);
   }
}
