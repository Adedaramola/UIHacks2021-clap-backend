<?php

namespace App\Providers;

use App\Interfaces\RepositoryInterface;
use App\Repository\Repository;
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
      $this->app->bind(RepositoryInterface::class, Repository::class);
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
