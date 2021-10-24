<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
         'email' => 'required|string|email',
         'password' => 'required|string',
      ];
   }

   public function authenticate()
   {
      $this->ensureRateIsNotLimited();

      $token = Auth::attempt($this->only('email', 'password'));

      if (!$token) {
         RateLimiter::hit($this->throttleKey());
         throw ValidationException::withMessages([
            'email' => __('auth.failed'),
         ]);

         RateLimiter::clear($this->throttleKey());
      }
      return $token;
   }

   protected function ensureRateIsNotLimited()
   {
      if (!RateLimiter::availableIn($this->throttleKey())) {
         return;
      }

      event(new Lockout($this));

      $seconds = RateLimiter::availableIn($this->throttleKey());

      throw ValidationException::withMessages([
         'email' => trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
         ]),
      ]);
   }

   protected function throttleKey()
   {
      return Str::lower($this->input('email')) . '|' . $this->ip();
   }
}
