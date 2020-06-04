<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouts', function (Blueprint $table) {
            $table->id();

            $table->double('value');
            $table->integer('n');
            $table->string('standard-key-hash-hex')->nullable();
            $table->string('standard-key-hash-address')->nullable();
            $table->string('witness-hex')->nullable();
            $table->integer('witness-lock-from-block')->nullable();
            $table->integer('witness-lock-until-block')->nullable();
            $table->integer('witness-fail-count')->nullable();
            $table->integer('witness-action-nonce')->nullable();
            $table->string('witness-pubkey-spend')->nullable();
            $table->string('witness-pubkey-witness')->nullable();
            $table->string('witness-address')->nullable();

            $table->integer('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions');

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
        Schema::dropIfExists('vouts');
    }
}
