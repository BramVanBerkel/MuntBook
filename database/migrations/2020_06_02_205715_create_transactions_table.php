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
            $table->string("id")->unique();
            $table->integer('block_height');

            $table->integer('size');
            $table->integer('vsize');
            $table->integer('version');
            $table->integer('locktime');
            $table->string('blockhash');
            $table->integer('confirmations');
            $table->dateTime('blocktime');

            $table->foreign('block_height')->references('height')->on('blocks')->cascadeOnDelete();

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
