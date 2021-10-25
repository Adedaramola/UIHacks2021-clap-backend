<?php

namespace App\Listeners;

use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeNotification
{

   /**
    * Handle the event.
    *
    * @param  object  $event
    * @return void
    */
   public function handle(Registered $event)
   {
      $event->user->notify(new WelcomeNotification($event->user));
   }
}
