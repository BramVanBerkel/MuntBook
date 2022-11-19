<?php

namespace App\Jobs;

use App\Services\MuntService;
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
     * @throws GuzzleException
     */
    public function handle(MuntService $muntService)
    {
        $difficulty = $muntService->getDifficulty();

        Cache::put('difficulty', round($difficulty));
    }
}
