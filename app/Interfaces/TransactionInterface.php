<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface TransactionInterface
{
   public function store(array $attributes): Model;
}
