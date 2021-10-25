<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;

class CreateNewUserWallet
{
   private $user;
   /**
    * Create the event listener.
    *
    * @return void
    */
   public function __construct(User $user)
   {
      $this->user = $user;
   }

   /**
    * Handle the event.
    *
    * @param  object  $event
    * @return void
    */
   public function handle($event)
   {
      $event->user->wallet()->create([
         'id' => '@' . $event->user->username,
         'pin' => Hash::make('1234'),
      ]);
   }
}
