<?php

use App\Models\WitnessAddressPart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWitnessAddressPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('witness_address_parts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('address_id');
            $table->foreign('address_id')->references('id')->on('addresses');

            $table->enum('type', WitnessAddressPart::TYPES);
            $table->unsignedInteger('age');
            $table->double('amount');
            $table->unsignedInteger('raw_weight');
            $table->unsignedInteger('adjusted_weight');
            $table->unsignedInteger('adjusted_weight_final');
            $table->unsignedInteger('expected_witness_period');
            $table->unsignedInteger('estimated_witness_period');
            $table->unsignedInteger('last_active_block');
            $table->unsignedInteger('lock_from_block');
            $table->unsignedInteger('lock_until_block');
            $table->unsignedInteger('lock_period');
            $table->boolean('lock_period_expired');
            $table->boolean('eligible_to_witness');
            $table->boolean('expired_from_inactivity');
            $table->unsignedInteger('fail_count');
            $table->unsignedInteger('action_nonce');

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
        Schema::dropIfExists('witness_address_parts');
    }
}
