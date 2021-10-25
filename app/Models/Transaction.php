<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Transaction extends Model
{
   use HasFactory;

   protected $fillable = [
      'txn_type',
      'reference',
      'external_ref',
      'purpose',
      'amount',
      'narration',
      'wallet_id',
      'balance_before',
      'balance_after'
   ];

   const E_INVALID_MODEL = 'Model must contain hash and previousHash columns';
   const E_IMMUTABLE_CLASS = '';
   const E_INTEGRITY_LEVEL1 = 'Data in this table has been breached';
   const E_INTEGRITY_LEVEL2 = 'Data in this table has been breached';

   // public function __construct()
   // {
   //    parent::__construct();
   //    $this->checkIntegrity();
   // }

   public function wallet()
   {
      return $this->belongsTo(Wallet::class);
   }

   protected static function boot()
   {
      parent::boot();

      self::creating(function ($model) {
         $previous = DB::table('transactions')->orderBy('id', 'desc')->first();
         $model->previousHash = $previous ? $previous->hash : null;
         $model->hash = static::generateHash($model->getAttributes());
         $model->reference = Str::random(18);
      });

      self::deleting(function () {
         throw new \Exception(self::E_IMMUTABLE_CLASS);
      });

      self::updating(function () {
         throw new \Exception(self::E_IMMUTABLE_CLASS);
      });
   }


   public function checkIntegrity()
   {

      DB::table('transactions')->orderBy('id', 'asc')->chunk(100, function ($records) {
         $previous = null;
         foreach ($records as $record) {
            if ($previous && $previous !== $record->previousHash) {
               throw new \Exception(self::E_INTEGRITY_LEVEL1);
            }
         }

         foreach ($records as $record) {
            if ($record->hash && $record->hash !== static::generateHash((array)$record)) {
               throw new \Exception(self::E_INTEGRITY_LEVEL2);
            }
         }

         return false;
      });

      return true;
   }

   private static function generateHash($record)
   {
      unset($record['hash']);
      unset($record['id']);
      ksort($record);
      $salt = config('app.key');
      $data = json_encode($record);

      return hash('sha256', $data . $salt);
   }
}
