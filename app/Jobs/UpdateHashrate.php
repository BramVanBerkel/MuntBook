<?php

namespace App\Jobs;

use App\Services\MuntService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateHashrate implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(MuntService $muntService)
    {
        // hashrate in hashes per second
        $hashrate = $muntService->getNetworkHashrate();

        //convert hashrate to Kh/s if it's less than 1 Mh/s (for testnet)
        $hashrate = ($hashrate / 1_000_000 < 1) ?
            round($hashrate / 1000, 2).' Kh/s' :
            round($hashrate / 1_000_000, 2).' Mh/s';

        Cache::put('hashrate', $hashrate);
    }
}
