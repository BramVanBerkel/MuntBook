<?php

use App\Models\Vin;
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

            $table->foreignId('transaction_id')->index();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();

            $table->foreignId('vout_id')->nullable()->index();
            $table->foreign('vout_id')->references('id')->on('vouts')->cascadeOnDelete();

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
