<?php

namespace App\Jobs;

use App\Repositories\BlockRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VinRepository;
use App\Services\BlockService;
use App\Services\GuldenService;
use App\Services\TransactionService;
use App\Services\VinService;
use App\Services\VoutService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessBlock implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private int $height,
    ) {}

    public function handle(
        GuldenService $guldenService,
        BlockService $blockService,
        TransactionService $transactionService,
        VoutService $voutService,
        VinService $vinService)
    {
        $blockData = $guldenService->getBlock($guldenService->getBlockHash($this->height), 1);

        DB::beginTransaction();

        $block = $blockService->saveBlock($blockData);

        if($block->hashps === null) {
            dispatch(new SetHashrate($block->height));
        }

        $block->transactions()->delete();

        foreach ($blockData->get('tx') as $txid) {
            $tx = $guldenService->getTransaction($txid, true);

            $transaction = $transactionService->saveTransaction($tx, $block);

            $vinService->saveVins($tx->get('vin'), $transaction);

            $voutService->saveVouts($tx->get('vout'), $transaction);
        }

        DB::commit();

        if($block->isWitness() && $block->transactions()->count() < 2) {
            dispatch((new ProcessBlock($this->height)))->delay(now()->addSeconds(config('gulden.sync_delay')));
        }
    }
}
