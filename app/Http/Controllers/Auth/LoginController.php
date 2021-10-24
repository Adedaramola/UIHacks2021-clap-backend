<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
   public function login(LoginRequest $request)
   {
      $token = $request->authenticate();
      return $this->sendTokenResponse($token);
   }

   public function logout()
   {
      Auth::logout();

      return response()->json(null, 204);
   }
}
