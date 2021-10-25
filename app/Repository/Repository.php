<?php

namespace App\Repository;

use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
   protected $model;


   public function __construct(Model $model)
   {
      $this->model = $model;
   }


   public function create(array $attributes): Model
   {
      return $this->model->create($attributes);
   }
   

   public function find($id): ?Model
   {
      return $this->model->find($id);
   }
}
