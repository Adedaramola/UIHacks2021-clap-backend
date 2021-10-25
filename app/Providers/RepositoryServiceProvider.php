<?php

namespace App\Providers;

use App\Interfaces\RepositoryInterface;
use App\Interfaces\TransactionInterface;
use App\Interfaces\WalletInterface;
use App\Repository\Repository;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
   /**
    * Register services.
    *
    * @return void
    */
   public function register()
   {
      $this->app->bind(
         RepositoryInterface::class,
         Repository::class
      );

      $this->app->bind(
         TransactionInterface::class,
         TransactionRepository::class
      );

      $this->app->bind(
         WalletInterface::class,
         WalletRepository::class
      );
   }

   /**
    * Bootstrap services.
    *
    * @return void
    */
   public function boot()
   {
      //
   }
}
