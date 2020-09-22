<?php

namespace App\Console\Commands\Gulden;

use App\Services\GuldenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateHashrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gulden:update-network-hashrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the network hashrate and stores it in cache';

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
    public function handle(GuldenService $guldenService)
    {
        // hashrate in hashes per second
        $hashrate = $guldenService->getNetworkHashrate();

        //convert hashrate to Kh/s if it's less than 1 Mh/s (for testnet)
        $hashrate = ($hashrate / 1000000 < 1) ?
            round($hashrate / 1000, 2).' Kh/s' :
            round($hashrate / 1000000, 2).' Mh/s';

        Cache::put('hashrate', $hashrate);
    }
}
