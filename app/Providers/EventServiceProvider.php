<?php

namespace App\Providers;

use App\Events\NewCreditTransaction;
use App\Events\NewDebitTransaction;
use App\Listeners\CreateNewUserWallet;
use App\Listeners\SendCreditTransactionReceipt;
use App\Listeners\SendDebitTransactionReceipt;
use App\Listeners\SendWelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
   /**
    * The event listener mappings for the application.
    *
    * @var array
    */
   protected $listen = [
      Registered::class => [
         SendEmailVerificationNotification::class,
         CreateNewUserWallet::class,
      ],

      NewDebitTransaction::class => [
         SendDebitTransactionReceipt::class,
      ],

      NewCreditTransaction::class => [
         SendCreditTransactionReceipt::class,
      ],

      Verified::class => [
         SendWelcomeNotification::class,
      ]
   ];

   /**
    * Register any events for your application.
    *
    * @return void
    */
   public function boot()
   {
   }
}
