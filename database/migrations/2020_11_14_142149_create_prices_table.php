<?php

use App\Models\Price;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();

            $table->timestamp('timestamp')->index();
            $table->unsignedInteger('open');
            $table->unsignedInteger('high');
            $table->unsignedInteger('low');
            $table->unsignedInteger('close');
            $table->decimal('volume', 16, 8);
            $table->enum('source', Price::SOURCES);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
