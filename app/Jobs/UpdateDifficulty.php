<?php

namespace App\Jobs;

use App\Services\GuldenService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateDifficulty implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  GuldenService  $guldenService
     *
     * @throws GuzzleException
     */
    public function handle(GuldenService $guldenService)
    {
        $difficulty = $guldenService->getDifficulty();

        Cache::put('difficulty', round($difficulty));
    }
}
