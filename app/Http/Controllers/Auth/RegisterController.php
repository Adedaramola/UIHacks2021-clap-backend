<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
   public function store(RegisterUserRequest $request)
   {
      $user = User::create([
         'name' => $request->name,
         'username' => $request->username,
         'email' => $request->email,
         'phone' => $request->phone,
         'password' => Hash::make($request->password)
      ]);

      event(new Registered($user));

      $token = Auth::attempt($request->only('email', 'password'));

      return $this->sendTokenResponse($token, 'Verify your email');
   }
}
