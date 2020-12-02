<?php

namespace App\Jobs;

use App\Services\GuldenService;
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

    /**
     * @param GuldenService $guldenService
     * @throws \GuzzleHttp\Exception\GuzzleException
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
