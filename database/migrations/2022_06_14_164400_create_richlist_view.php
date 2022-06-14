<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
            CREATE MATERIALIZED VIEW richlist AS SELECT
                addresses.address AS address,
                coalesce(sum(inputs.value), 0) - coalesce(sum(outputs.value), 0) AS value
            FROM
                addresses
                LEFT JOIN vouts AS inputs ON inputs.address_id = addresses.id
                LEFT JOIN vins ON vins.vout_id = inputs.id
                LEFT JOIN vouts AS outputs ON vins.vout_id = outputs.id
                WHERE addresses.type = 'ADDRESS'
                GROUP BY addresses.id
                WITH NO DATA;
        SQL);

        DB::statement(<<<SQL
            CREATE UNIQUE INDEX addresss ON richlist(address);
        SQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(<<<SQL
            DROP MATERIALIZED VIEW richlist;
        SQL);
    }
};
