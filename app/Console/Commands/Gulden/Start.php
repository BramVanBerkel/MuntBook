<?php

namespace App\Console\Commands\Gulden;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gulden:start {--gui : Whether to start Gulden for debugging, or the Gulden daemon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts Gulden service from binaries folder.';

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
        if (!file_exists("binaries")) {
            Log::error('binaries folder not found!');
            exit();
        }

        if (!file_exists("binaries/datadir")) {
            mkdir("binaries/datadir");
        }

        if (!file_exists("binaries/datadir/Gulden.conf")) {
            $conf = "disablewallet=1\n";
            $conf .= "txindex=1\n";
            $conf .= "server=1\n";
            $conf .= "blocknotify=" . env('GULDEN_BLOCKNOTIFY') . "\n";
            $conf .= "rpcuser=" . config('gulden.rpc_user') . "\n";
            $conf .= "rpcpassword=" . config('gulden.rpc_password') . "\n";

            file_put_contents("binaries/datadir/Gulden.conf", $conf);
        }

        $binary = ($this->option("gui")) ? "Gulden" : "GuldenD";

        exec("./binaries/{$binary} -datadir=binaries/datadir");
    }
}
