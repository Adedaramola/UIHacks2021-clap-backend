<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('wallets', function (Blueprint $table) {
         $table->uuid('id')->primary();
         $table->string('tag')->index();
         $table->foreignId('user_id')->constrained()->cascadeOnDelete();
         $table->integer('balance')->default(0);
         $table->string('pin');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('wallets');
   }
}
