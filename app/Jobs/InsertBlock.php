<?php

namespace App\Jobs;

use App\Models\Block;
use App\Services\Gulden;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class InsertBlock implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var int
     */
    private $blockData;

    /**
     * Create a new job instance.
     *
     * @param int $blockData
     */
    public function __construct(int $blockData)
    {
        $this->blockData = $blockData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $guldenService = resolve(Gulden::class);
        $blockData = $guldenService->getBlock($guldenService->getBlockHash($this->blockData));

        $block = new Block();

        $block->hash = $blockData->get('hash');
        $block->confirmations = $blockData->get('confirmations');
        $block->strippedsize = $blockData->get('strippedsize');
        $block->validated = $blockData->get('validated');
        $block->size = $blockData->get('size');
        $block->weight = $blockData->get('weight');
        $block->height = $blockData->get('height');
        $block->version = $blockData->get('version');
        $block->versionHex = $blockData->get('versionHex');
        $block->merkleroot = $blockData->get('merkleroot');
        $block->witness_version = $blockData->get('witness_version');
        $block->witness_versionHex = $blockData->get('witness_versionHex');
        $block->witness_time = $blockData->get('witness_time');
        $block->pow_time = $blockData->get('pow_time');
        $block->witness_merkleroot = $blockData->get('witness_merkleroot');
        $block->time = $blockData->get('time');
        $block->mediantime = $blockData->get('mediantime');
        $block->nonce = $blockData->get('nonce');
        $block->pre_nonce = $blockData->get('pre_nonce');
        $block->post_nonce = $blockData->get('post_nonce');
        $block->bits = $blockData->get('bits');
        $block->difficulty = $blockData->get('difficulty');
        $block->chainwork = $blockData->get('chainwork');
        $block->previousblockhash = $blockData->get('previousblockhash');

        $block->save();
    }
}
