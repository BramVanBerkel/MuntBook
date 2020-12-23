<?php

use App\Models\Vout;
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

            $table->foreignId('transaction_id')->index();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();

            $table->foreignId('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses')->cascadeOnDelete();

            $table->enum('type', [
                Vout::TYPE_TRANSACTION,
                Vout::TYPE_MINING,
                Vout::TYPE_WITNESS,
                Vout::TYPE_WITNESS_FUNDING,
                Vout::TYPE_WITNESS_COMPOUND,
            ]);

            $table->double('value');
            $table->integer('n');
            $table->string('standard_key_hash_hex')->nullable();
            $table->string('standard_key_hash_address')->nullable();
            $table->string('scriptpubkey_type')->nullable();
            $table->string('witness_hex')->nullable();
            $table->integer('witness_lock_from_block')->nullable();
            $table->integer('witness_lock_until_block')->nullable();
            $table->integer('witness_fail_count')->nullable();
            $table->integer('witness_action_nonce')->nullable();
            $table->string('witness_pubkey_spend')->nullable();
            $table->string('witness_pubkey_witness')->nullable();

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
