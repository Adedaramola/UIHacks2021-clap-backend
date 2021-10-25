<?php

namespace App\Interfaces;

interface PaymentInterface
{
   public function fundWithCard();

   public function fundWithBank();
}
