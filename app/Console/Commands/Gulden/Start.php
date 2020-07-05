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
    protected $signature = 'gulden:start {--binary= : Manually select the binary to start}';

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
     * @return void
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

        $conf = "disablewallet=1\n";
        $conf .= "txindex=1\n";
        $conf .= "server=1\n";
        $conf .= "blocknotify=" . env('GULDEN_BLOCKNOTIFY') . "\n";
        $conf .= "rpcuser=" . config('gulden.rpc_user') . "\n";
        $conf .= "rpcpassword=" . config('gulden.rpc_password') . "\n";

        file_put_contents("binaries/datadir/Gulden.conf", $conf);

        if ($binary = $this->option("binary")) {
            if (!in_array($binary, ["Gulden", "GuldenD"])) {
                Log::error("Invalid binary given");
                exit();
            }
        } else {
            $binary = config('app.env') === 'local' ? 'Gulden' : 'GuldenD';
        }

        exec("./binaries/{$binary} -datadir=binaries/datadir");
    }
}
