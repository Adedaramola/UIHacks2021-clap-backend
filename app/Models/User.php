<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements JWTSubject
{
   use HasFactory, Notifiable;

   protected $fillable = [
      'username',
      'name',
      'email',
      'phone',
      'password',
      'type'
   ];


   protected $hidden = [
      'password',
      'remember_token',
   ];


   protected $casts = [
      'email_verified_at' => 'datetime',
   ];


   public function wallet()
   {
      return $this->hasOne(Wallet::class);
   }



   public function sendPasswordResetNotification($token)
   {
      $this->notify(new ResetPassword($token));
   }


   public function sendEmailVerificationNotification()
   {
      $this->notify(new VerifyEmail);
   }

   public function getJWTIdentifier()
   {
      return $this->getKey();
   }


   public function getJWTCustomClaims()
   {
      return [];
   }

}
