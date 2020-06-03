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
            $table->id();

            $table->string("txid"); //TODO: check if txid and hash are the same
            $table->string("hash");
            $table->integer('size');
            $table->integer('vsize');
            $table->integer('version');
            $table->integer('locktime');
            $table->string('blockhash');
            $table->integer('confirmations');
            $table->integer('time');
            $table->integer('blocktime');

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
        Schema::dropIfExists('transactions');
    }
}
