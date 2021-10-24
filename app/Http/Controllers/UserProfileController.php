<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
   public function __invoke(Request $request)
   {
      $id = $request->user()->id;
      return new UserResource(User::findOrFail($id));
   }
}
