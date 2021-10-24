<?php

namespace App\Services;

use App\Facades\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;


class WalletService
{
   public function credit(int $amount, $wallet_id, $purpose = 'transfer')
   {
      $wallet = Wallet::find($wallet_id);

      if (!$wallet) {
         throw new \Exception('Wallet not found');
      }

      DB::beginTransaction();

      try {
         DB::table('wallets')
            ->where('tag', $wallet_id)
            ->increment('balance', $amount);

         Transaction::create(
            'credit',
            $purpose,
            $amount,
            $wallet_id,
            $wallet->balance,
            $wallet->balance + $amount
         );

         DB::commit();

         return true;
      } catch (\Exception $e) {
         DB::rollBack();

         throw new \Exception($e->getMessage());
      }
   }


   /**
    * debit
    *
    * @param  int $amount
    * @param  string $wallet_id
    * @param  string $purpose
    * @return void
    */
   public function debit(int $amount, string $wallet_id, $purpose = 'transfer')
   {
      $wallet = Wallet::find($wallet_id);

      if (!$wallet) {
         throw new \Exception('Wallet not found');
      }

      if ($wallet->balance < $amount) {
         throw new \Exception('Insufficient balance');
      }

      DB::beginTransaction();

      try {
         DB::table('wallets')
            ->where('tag', $wallet_id)
            ->decrement('balance', $amount);

         Transaction::create(
            'debit',
            $purpose,
            $amount,
            $wallet_id,
            $wallet->balance,
            $wallet->balance - $amount
         );

         DB::commit();

         return true;
      } catch (\Exception $e) {
         DB::rollBack();

         throw new \Exception($e->getMessage());
      }
   }

   public function transfer($sender_id, $receiver_id, $amount)
   {
      $wallet1 = Wallet::where('tag', $sender_id)->first();
      $wallet2 = Wallet::where('tag', $receiver_id)->first();

      if (!($wallet1 && $wallet2)) {
         return response()->json([
            'success' => false,
            'message' => 'Wallets not found'
         ]);
      }


      if ($sender_id === $receiver_id) {
         return response()->json([
            'status' => false,
            'message' => 'Impossible action'
         ]);
      }


      DB::beginTransaction();

      try {

         try {
            $this->debit($amount, $sender_id);
         } catch (\Exception $e) {
            return response()->json([
               'status' => false,
               'message' => $e->getMessage()
            ]);
         }

         $this->credit($amount, $receiver_id);

         DB::commit();

         return response()->json([
            'status' => true,
            'message' => 'Transfer successful'
         ]);
      } catch (\Exception $e) {
         DB::rollBack();

         return response()->json([
            'status' => false,
            'message' => $e->getMessage()
         ], 500);
      }
   }
}
