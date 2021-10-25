<?php

namespace App\Repository;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TransactionRepository extends Repository implements TransactionInterface
{
   public function __construct(Transaction $model)
   {
      parent::__construct($model);
   }

   public function store(array $attributes): Model
   {
      return $this->model->create([
         'reference' => 'TRX' . Str::random(15),
         'external_reference' => $attributes['external_reference'] ?? '',
         'txn_type' => $attributes['txn_type'],
         'purpose' => $attributes['purpose'],
         'amount' => $attributes['amount'],
         'wallet_id' => $attributes['wallet_id'],
         'balance_before' => $attributes['balance_before'],
         'balance_after' => $attributes['balance_after']
      ]);
   }
}
