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

        //unzip
        $zipPath = realpath('database/seeds/bittrex.csv.zip');
        $zipArchive = new \ZipArchive();

        \Log::channel('stderr')->info('Opening bittrex.csv.zip...');
        $opened = $zipArchive->open($zipPath);
        if (!$opened) {
            \Log::channel('stderr')->info('Failed opening bittrex.csv.zip!');
            die();
        }

        \Log::channel('stderr')->info('Unzipping bittrex.csv.zip...');
        $extracted = $zipArchive->extractTo('database/seeds');
        if (!$extracted) {
            \Log::channel('stderr')->info('Failed extracting bittrex.csv.zip!');
            die();
        }

        $path = realpath('database/seeds/bittrex.csv');

        //import
        \Log::channel('stderr')->info('Importing bittrex.csv.zip...');
        DB::statement(<<<SQL
            COPY prices(timestamp, open, high, low, close, volume, source)
            FROM '{$path}'
            DELIMITER ','
            CSV HEADER;
        SQL
        );

        //cleanup
        \Log::channel('stderr')->info('Removing bittrex.csv.zip...');
        unlink($path);
    }
}
