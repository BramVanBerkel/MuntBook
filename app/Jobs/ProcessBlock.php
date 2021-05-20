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
use Illuminate\Support\Facades\DB;
use Throwable;

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
        BlockRepository $blockRepository,
        TransactionRepository $transactionRepository,
        VoutRepository $voutRepository,
        VinRepository $vinRepository)
    {
        $blockData = $guldenService->getBlock($guldenService->getBlockHash($this->height), 1);

        DB::beginTransaction();

        $block = $blockRepository->syncBlock($blockData);

        if($block->hashps === null) {
            dispatch(new SetHashrate($block->height));
        }

        $block->transactions()->delete();

        foreach ($blockData->get('tx') as $txid) {
            $tx = $guldenService->getTransaction($txid, true);

            $transaction = $transactionRepository->syncTransaction($tx, $block);

            $voutRepository->syncVouts($tx->get('vout'), $transaction);

            $vinRepository->syncVins($tx->get('vin'), $transaction);
        }

        DB::commit();

        if($block->isWitness() && $block->transactions()->count() < 2) {
            dispatch((new ProcessBlock($this->height)))->delay(now()->addSeconds(config('gulden.sync_delay')));
        }
    }
}
