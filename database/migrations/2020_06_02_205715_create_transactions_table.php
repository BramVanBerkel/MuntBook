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
            $table->integer('block_id');
            $table->foreign('block_id')->references('height')->on('blocks')->cascadeOnDelete();

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
