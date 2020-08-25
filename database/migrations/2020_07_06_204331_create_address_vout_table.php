<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressVoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_vout', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('address_id')->index();
            $table->foreign('address_id')->references('id')->on('addresses')->cascadeOnDelete();
            $table->index('address_id');

            $table->unsignedBigInteger('vout_id')->index();
            $table->foreign('vout_id')->references('id')->on('vouts')->cascadeOnDelete();
            $table->index('vout_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_vout');
    }
}
