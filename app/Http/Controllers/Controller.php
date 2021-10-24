<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
   use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

   public function sendResponse($data, $code = 200)
   {
      return response()->json([
         'status' => true,
         'data' => $data
      ], $code);
   }

   public function sendTokenResponse($token, $message = 'Success', $status = true)
   {
      return response()->json([
         'status' => $status,
         'message' => $message,
         'token' => $token,
         'token_type' => 'Bearer'
      ]);
   }
}
