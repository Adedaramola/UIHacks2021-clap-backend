<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
   public function toArray($request)
   {
      return [
         'status' => true,
         'data' => [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'wallet' => [
               'tag' => $this->wallet->tag,
               'balance' => $this->wallet->balance
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
         ]
      ];
   }
}
