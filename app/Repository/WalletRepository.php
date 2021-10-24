<?php

namespace App\Repository;

use App\Models\Wallet;

class WalletRepository extends Repository
{
   public function __construct(Wallet $wallet)
   {
      parent::__construct($wallet);
   }
   public function creditOrDebit()
   {
   }
}
