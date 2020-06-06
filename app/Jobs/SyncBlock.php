<?php

namespace App\Jobs;

use App\Models\Block;
use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use App\Services\Gulden;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
     * @return void
     */
    public function handle()
    {
        $guldenService = resolve(Gulden::class);
        $blockData = $guldenService->getBlock($guldenService->getBlockHash($this->height));
        $blockData = collect($blockData);

        DB::beginTransaction();

        $block = Block::create([
            'height' => $blockData->get('height'),
            'hash' => $blockData->get('hash'),
            'confirmations' => $blockData->get('confirmations'),
            'strippedsize' => $blockData->get('strippedsize'),
            'validated' => $blockData->get('validated'),
            'size' => $blockData->get('size'),
            'weight' => $blockData->get('weight'),
            'version' => $blockData->get('version'),
            'versionHex' => $blockData->get('versionHex'),
            'merkleroot' => $blockData->get('merkleroot'),
            'witness_version' => $blockData->get('witness_version'),
            'witness_versionHex' => $blockData->get('witness_versionHex'),
            'witness_time' => $blockData->get('witness_time'),
            'pow_time' => $blockData->get('pow_time'),
            'witness_merkleroot' => $blockData->get('witness_merkleroot'),
            'time' => $blockData->get('time'),
            'mediantime' => $blockData->get('mediantime'),
            'nonce' => $blockData->get('nonce'),
            'pre_nonce' => $blockData->get('pre_nonce'),
            'post_nonce' => $blockData->get('post_nonce'),
            'bits' => $blockData->get('bits'),
            'difficulty' => $blockData->get('difficulty'),
            'chainwork' => $blockData->get('chainwork'),
            'previousblockhash' => $blockData->get('previousblockhash'),
        ]);

        foreach ($blockData->get('tx') as $tx) {
            $tx = collect($tx);

            $transaction = Transaction::create([
                'txid' => $tx->get('txid'),
                'hash' => $tx->get('hash'),
                'size' => $tx->get('size'),
                'vsize' => $tx->get('vsize'),
                'version' => $tx->get('version'),
                'locktime' => $tx->get('locktime'),
                'block_id' => $block->height,
            ]);

            foreach ($tx->get('vin') as $vin){
                $vin = collect($vin);

                Vin::create([
                    'prevout_type' => $vin->get('prevout_type'),
                    'txid' => $vin->get('txid'),
                    'coinbase' => $vin->get('coinbase'),
                    'tx_height' => $vin->get('tx_height') !== "" ? $vin->get('tx_height') : null,
                    'tx_index' => $vin->get('tx_index') !== "" ? $vin->get('tx_index') : null,
                    'scriptSig_asm' => $vin->get('scriptSig_asm') !== "" ? $vin->get('scriptSig_asm') : null,
                    'scriptSig_hex' => $vin->get('scriptSig_hex') !== "" ? $vin->get('scriptSig_hex') : null,
                    'vout' => $vin->get('vout'),
                    'rbf' => $vin->get('rbf'),
                    'transaction_id' => $transaction->id
                ]);
            }

            foreach ($tx->get('vout') as $vout) {
                $vout = collect($vout);

                Vout::create([
                    'value' => $vout->get('value'),
                    'n' => $vout->get('n'),
                    'standard-key-hash-hex' => optional($vout->get('standard-key-hash'))->hex,
                    'standard-key-hash-address' => optional($vout->get('standard-key-hash'))->address,
                    'witness-hex' => optional($vout->get('PoW²-witness'))->hex,
                    'witness-lock-from-block' => optional($vout->get('PoW²-witness'))->lock_from_block,
                    'witness-lock-until-block' => optional($vout->get('PoW²-witness'))->lock_until_block,
                    'witness-fail-count' => optional($vout->get('PoW²-witness'))->fail_count,
                    'witness-action-nonce' => optional($vout->get('PoW²-witness'))->action_nonce,
                    'witness-pubkey-spend' => optional($vout->get('PoW²-witness'))->pubkey_spend,
                    'witness-pubkey-witness' => optional($vout->get('PoW²-witness'))->pubkey_witness,
                    'witness-address' => optional($vout->get('PoW²-witness'))->address,

                    'transaction_id' => $transaction->id
                ]);
            }
        }
        DB::commit();

        Cache::forget("syncblock-{$this->height}");
    }
}
