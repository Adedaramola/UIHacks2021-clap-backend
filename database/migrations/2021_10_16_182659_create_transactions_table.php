<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('transactions', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->string('wallet_tag');
         $table->uuid('reference')->unique()->index();
         $table->enum('txn_type', ['credit', 'debit']);
         $table->enum('purpose', ['deposit', 'transfer', 'withdrawal', 'reversal']);
         $table->string('external_reference')->nullable()->index();
         $table->integer('amount');
         $table->string('narration')->nullable();
         $table->integer('balance_before');
         $table->integer('balance_after');
         $table->string('hash')->index()->nullable();
         $table->string('previousHash')->index()->nullable();
         $table->timestamps();

         $table->foreign('wallet_tag')->references('tag')->on('wallets');
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('transactions');
   }
}
