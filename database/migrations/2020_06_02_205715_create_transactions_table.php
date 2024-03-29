<?php

use App\Models\Transaction;
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

            $table->foreignId('block_height')->index();
            $table->foreign('block_height')->references('height')->on('blocks')->cascadeOnDelete();

            $table->string('txid')->unique();
            $table->integer('size');
            $table->integer('vsize');
            $table->integer('version');
            $table->integer('locktime');
            $table->string('blockhash');
            $table->integer('confirmations');
            $table->dateTime('blocktime');
            $table->enum('type', Transaction::TYPES);

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
        Schema::dropIfExists('transactions');
    }
}
