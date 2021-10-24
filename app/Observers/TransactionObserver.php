<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    public $afterCommit = true;


    public function created(Transaction $transaction)
    {
       
    }
}
