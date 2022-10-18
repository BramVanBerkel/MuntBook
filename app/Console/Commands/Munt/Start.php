<?php

namespace App\Console\Commands\Munt;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Start extends Command
{
    protected $signature = 'munt:start {--binary= : Manually select the binary to start}';

    protected $description = 'Starts Munt service from binaries folder.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! file_exists('binaries')) {
            Log::error('binaries folder not found!');
            exit();
        }

        if (! file_exists('binaries/munt/datadir')) {
            mkdir('binaries/munt/datadir');
        }

        $config = "txindex=1\n";
        $config .= "server=1\n";
        $config .= 'blocknotify='.config('munt.blocknotify')."\n";
        $config .= 'rpcuser='.config('munt.rpc_user')."\n";
        $config .= 'rpcpassword='.config('munt.rpc_password')."\n";
        $config .= 'port='.config('munt.port')."\n";

        if (config('munt.maxconnections')) {
            $config .= 'maxconnections='.config('munt.maxconnections')."\n";
        }

        if (! empty(config('munt.testnet'))) {
            $config .= 'testnet='.config('munt.testnet')."\n";
        }

        if (! empty(config('munt.addnode'))) {
            $nodes = explode(',', (string) config('munt.addnode'));
            foreach ($nodes as $node) {
                $config .= "addnode={$node}\n";
            }
        }

        file_put_contents('binaries/munt/datadir/Munt.conf', $config);

        $command = "./binaries/munt/Munt-daemon -datadir=binaries/munt/datadir";

        if (! empty(config('munt.testnet'))) {
            $command = sprintf("{$command} -testnet=%s", config('munt.testnet'));
        }

        exec($command);
    }
}
