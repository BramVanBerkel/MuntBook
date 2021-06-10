<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoipBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geoip_blocks', function (Blueprint $table) {
            $table->id();

            $table->ipAddress('cidr');
            $table->foreignId('geoname_id')->nullable()->references('geoname_id')->on('geoip_locations');

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
        Schema::dropIfExists('geoip_blocks');
    }
}
