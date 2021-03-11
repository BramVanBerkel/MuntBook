<?php

namespace App\Jobs;

use App\Repositories\WitnessAddressRepository;
use App\Services\GuldenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncWitnessInfo implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(GuldenService $guldenService)
    {
        $witnessInfo = $guldenService->getWitnessInfo(verbose: true);

        $witnessInfo->get('witness_address_list')->groupBy('address')->each(function($parts) {
            WitnessAddressRepository::syncParts($parts);
        });
    }
}
