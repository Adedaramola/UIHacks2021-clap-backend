<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
   public function authorize()
   {
      return true;
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
      return [
         'username' => 'required|string|unique:users',
         'name' => 'required|string',
         'email' => 'required|string|email|indisposable|unique:users',
         'phone' => 'required|string|size:11',
         'type' => ['required', 'string', Rule::in(['user', 'merchant'])],
         'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()]
      ];
   }
}
