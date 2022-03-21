<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id('height');

            $table->string('hash')->unique();
            $table->unsignedInteger('confirmations');
            $table->unsignedInteger('strippedsize');
            $table->unsignedInteger('validated');
            $table->unsignedInteger('size');
            $table->unsignedInteger('weight');
            $table->unsignedInteger('version');
            $table->string('merkleroot');
            $table->unsignedInteger('witness_version')->nullable();
            $table->dateTime('witness_time')->nullable();
            $table->dateTime('pow_time');
            $table->string('witness_merkleroot')->nullable();
            $table->dateTime('time');
            $table->unsignedBigInteger('nonce');
            $table->unsignedInteger('pre_nonce');
            $table->unsignedInteger('post_nonce');
            $table->string('bits');
            $table->double('difficulty');
            $table->double('hashrate')->nullable();
            $table->unsignedBigInteger('chainwork')->comment("Stored in gigahashes (number / 1.000.000.000)");
            $table->string('previousblockhash')->nullable();

            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
}
