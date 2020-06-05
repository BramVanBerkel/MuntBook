<?php

namespace App\Console\Commands\Gulden;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Stop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gulden:stop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stops Gulden service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        exec("./binaries/Gulden-cli -datadir=binaries/datadir stop");
    }
}
