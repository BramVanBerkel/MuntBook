<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BittrexPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if prices have already been seeded
        if (Price::count() !== 0) {
            return;
        }

        $zipPath = realpath('database/seeders/bittrex.csv.zip');
        $zipArchive = new \ZipArchive();

        \Log::channel('stderr')->info('Unzipping bittrex.csv.zip');
        $zipArchive->open($zipPath);

        $zipArchive->extractTo('database/seeders');

        $path = realpath('database/seeders/bittrex.csv');

        \Log::channel('stderr')->info('Importing bittrex.csv...');
        DB::statement(<<<SQL
            COPY prices(timestamp, price, source)
            FROM '$path'
            DELIMITER ','
            CSV HEADER;
        SQL
        );

        //cleanup
        \Log::channel('stderr')->info('Removing bittrex.csv...');
        unlink($path);
    }
}
