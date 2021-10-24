<?php

namespace App\Facades;

use App\Services\WalletService;
use Illuminate\Support\Facades\Facade;

class Wallet extends Facade
{
   protected static function getFacadeAccessor()
   {
      return WalletService::class;
   }
}
