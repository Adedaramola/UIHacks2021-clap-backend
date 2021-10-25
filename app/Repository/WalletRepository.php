<?php

namespace App\Repository;

use App\Events\NewCreditTransaction;
use App\Events\NewDebitTransaction;
use App\Interfaces\TransactionInterface;
use App\Interfaces\WalletInterface;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WalletRepository extends Repository implements WalletInterface
{
   private $transactionRepository;

   public function __construct(Wallet $model, TransactionInterface $transactionRepository)
   {
      parent::__construct($model);
      $this->transactionRepository = $transactionRepository;
   }


   public function credit(int $amount, $wallet_id, $purpose)
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

         $transaction = $this->transactionRepository->store([
            'txn_type' => 'Credit',
            'purpose' => $purpose,
            'amount' => $amount,
            'wallet_id' => $wallet_id,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance + $amount,
         ]);

         DB::commit();

         event(new NewCreditTransaction($transaction));

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
      $purpose
   ) {

      $wallet = $this->model->find($wallet_id);

      if (!$wallet) {
         return response()->json([
            'success' => false,
            'message' => 'Wallet with this tag does not exist'
         ]);
      }

      if ($wallet->balance < $amount) {
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

         $transaction = $this->transactionRepository->store([
            'txn_type' => 'Debit',
            'purpose' => $purpose,
            'amount' => $amount,
            'wallet_id' => $wallet_id,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance - $amount,
         ]);

         DB::commit();

         event(new NewDebitTransaction($transaction));

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

      if ($sender_wallet) {
         if (!Hash::check($pin, $sender_wallet->pin)) {
            return response()->json([
               'status' => false,
               'message' => 'Invalid pin entered'
            ]);
         }
      }

      DB::beginTransaction();

      try {
         $debitStatus = ($this->debit($amount, $sender, 'transfer'))->original['success'];
         $creditStatus = ($this->credit($amount, $receiver, 'transfer'))->original['success'];

         if (!$debitStatus || !$creditStatus) {
            return response()->json([
               'status' => false,
               'message' => 'Something went wrong. You have probably entered the wrong details'
            ]);
         }

         DB::commit();

         return response()->json([
            'status' => true,
            'message' => 'Transaction successfull'
         ]);
      } catch (\Exception $err) {
         DB::rollBack();

         return response()->json([
            'status' => false,
            'message' => 'Something went wrong: ' . $err->getMessage()
         ]);
      }
   }
}
