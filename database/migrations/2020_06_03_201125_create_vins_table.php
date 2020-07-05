<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vins', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');

            $table->string('prevout_type')->nullable();
            $table->string('txid')->nullable();
            $table->string('coinbase')->nullable();
            $table->integer('tx_height')->nullable();
            $table->integer('tx_index')->nullable();
            $table->string('scriptSig_asm')->nullable();
            $table->string('scriptSig_hex')->nullable();
            $table->integer('vout')->nullable();
            $table->integer('rbf')->nullable();

            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();

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
        Schema::dropIfExists('vins');
    }
}
