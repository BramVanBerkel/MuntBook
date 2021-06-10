<?php

use Database\Seeders\BittrexPricesSeeder;
use Database\Seeders\IpAddressesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(BittrexPricesSeeder::class);

         $this->call(IpAddressesSeeder::class);
    }
}
