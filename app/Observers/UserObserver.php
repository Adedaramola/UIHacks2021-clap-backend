<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
   public function created(User $user)
   {
      $user->wallet()->create([
         'tag' => '$' . Str::camel($user->username),
         'balance' => 500
      ]);
   }
}
