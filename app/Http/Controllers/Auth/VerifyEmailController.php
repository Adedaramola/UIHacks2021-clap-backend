<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
   public function verify(Request $request, User $user)
   {
      if ($user->hasVerifiedEmail()) {
         return response()->json([
            'status' => trans('verification.already_verified'),
         ], 400);
      }

      $user->markEmailAsVerified();
      event(new Verified($user));

      return response()->json([
         'status' => trans('verification.verified'),
      ]);
   }
}
