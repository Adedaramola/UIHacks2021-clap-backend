<?php

namespace App\Repository;

use App\Events\NewTransaction;
use App\Interfaces\WalletInterface;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WalletRepository extends Repository implements WalletInterface
{
   public function __construct(Wallet $model)
   {
      parent::__construct($model);
   }


   public function credit(int $amount, $wallet_id)
   {
      $wallet = $this->model->find($wallet_id);

      if (!$wallet) {
         return response()->json([
            'success' => false,
            'message' => 'Wallet with this tag does not exist'
         ]);
      }

      DB::beginTransaction();

      try {
         DB::table('wallets')
            ->where('id', $wallet_id)
            ->increment('balance', $amount);

         $transaction = $this->create([
            'txn_type' => 'Credit',
            'amount' => $amount,
            'wallet_id' => $wallet_id,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance + $amount,
         ]);

         DB::commit();

         event(new NewTransaction($transaction));

         return response()->json([
            'success' => true,
            'message' => 'Wallet credited successfully'
         ]);
      } catch (\Exception $err) {

         DB::rollBack();

         return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $err->getMessage()
         ], 500);
      }
   }


   public function debit(
      int $amount,
      $wallet_id,
   ) {

      $wallet = $this->model->find($wallet_id);

      if (!$wallet) {
         return response()->json([
            'success' => false,
            'message' => 'Wallet with this tag does not exist'
         ]);
      }

      if (!$wallet->balance < $amount) {
         return response()->json([
            'success' => false,
            'message' => 'Insufficient funds in wallet'
         ]);
      }

      DB::beginTransaction();

      try {
         DB::table('wallets')
            ->where('id', $wallet_id)
            ->decrement('balance', $amount);

         $transaction = $this->create([
            'txn_type' => 'Credit',
            'amount' => $amount,
            'wallet_id' => $wallet_id,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance - $amount,
         ]);

         DB::commit();

         event(new NewTransaction($transaction));

         return response()->json([
            'success' => true,
            'message' => 'Wallet debited successfully'
         ]);
      } catch (\Exception $err) {

         DB::rollBack();
         return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $err->getMessage()
         ], 500);
      }
   }


   public function payToWallet($pin, $sender, $receiver, int $amount)
   {
      $sender_wallet = $this->model->find($sender);
      $receiver_wallet = $this->model->find($receiver);

      if (!$sender_wallet) {
         return response()->json([
            'status' => false,
            'message' => 'Sender wallet not found'
         ]);
      }

      if (!$receiver_wallet) {
         return response()->json([
            'status' => false,
            'message' => 'Target wallet not found'
         ]);
      }

      if (!Hash::check($pin, $sender_wallet->pin)) {
         return response()->json([
            'status' => false,
            'message' => 'Invalid pin entered'
         ]);
      }

      DB::beginTransaction();

      try {
      } catch (\Exception $err) {
         DB::rollBack();
      }
   }
}
