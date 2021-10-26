<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface WalletInterface
{
   public function payToWallet($pin, $sender, $receiver, int $amount);

   public function credit(int $amount, $wallet_tag, $purpose);

   public function debit(int $amount,$wallet_tag, $purpose);
}
