<?php

namespace App\Jobs;

use App\Repositories\BlockRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VinRepository;
use App\Repositories\VoutRepository;
use App\Services\GuldenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncBlock implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var int
     */
    private $height;

    /**
     * Create a new job instance.
     *
     * @param int $height
     */
    public function __construct(int $height)
    {
        $this->height = $height;
    }

    /**
     * Execute the job.
     *
     * @param GuldenService $guldenService
     * @return void
     */
    public function handle(GuldenService $guldenService)
    {
        $blockData = $guldenService->getBlock($guldenService->getBlockHash($this->height), 1);

        Log::info(sprintf("Processing block #{$this->height}"));

        $block = BlockRepository::create($blockData);

        foreach ($blockData->get('tx') as $txid) {
            $tx = $guldenService->getTransaction($txid, true);

            $transaction = TransactionRepository::create($tx, $block->height);
            foreach ($tx->get('vin') as $vin){
                VinRepository::create(collect($vin), $transaction->id);
            }

            foreach ($tx->get('vout') as $vout) {
                $vout = collect($vout);

                VoutRepository::create(collect($vout), $transaction->id);
            }
        }
    }
}
